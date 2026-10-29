import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
// Only the plugin's animation CSS — the bubbles are styled in _style.scss, so
// its default blue/yellow theme (MarkerCluster.Default.css) stays out.
import 'leaflet.markercluster'
import 'leaflet.markercluster/dist/MarkerCluster.css'
import { buildRefs, getJSON } from '@/assets/scripts/helpers.js'

// Cluster bubble grows in three steps so dense areas read heavier. The
// thresholds are calibrated to the size of the programme (a few dozen pins
// across the city), where a cluster of 7 is already dense — re-tune these if
// the event count grows by an order of magnitude.
const CLUSTER_STEPS = [
  { upTo: 3, name: 'sm', size: 40 },
  { upTo: 6, name: 'md', size: 52 },
  { upTo: Infinity, name: 'lg', size: 64 }
]

/**
 * Leaflet layer for the event map.
 *
 * All UI state (filters, selected entry) lives in the Alpine component in
 * index.twig; the two talk through custom events on the component element:
 *   in  — eventmap:filter { ids }, eventmap:selected { id }, eventmap:zoom { delta }
 *   out — eventmap:ready, eventmap:select { id }
 */
export default function (el) {
  const refs = buildRefs(el)
  const data = getJSON(el, 'script[data-ref="entries"]')
  const entries = Array.isArray(data) ? data : []
  if (!refs.map) return

  const [centerLat, centerLng] = (refs.map.dataset.center || '52.5200, 13.4050')
    .split(',')
    .map((n) => parseFloat(n.trim()))
  const zoom = parseInt(refs.map.dataset.zoom, 10) || 16

  const map = L.map(refs.map, {
    scrollWheelZoom: false, // see enableWheelZoom
    zoomSnap: 0, // fractional zoom, so scroll/pinch zoom is continuous
    zoomControl: false // replaced by the design's own zoom buttons
  }).setView([centerLat, centerLng], zoom)

  L.tileLayer(refs.map.dataset.tileUrl, {
    attribution: refs.map.dataset.tileAttribution,
    maxZoom: 19,
    // Upscale rather than request tiles the provider doesn't serve.
    maxNativeZoom: parseInt(refs.map.dataset.tileMaxNative, 10) || 19
  }).addTo(map)

  const pinShape = el.querySelector('template[data-ref="pinTemplate"]')
  const pinSvg = pinShape ? pinShape.innerHTML.trim() : ''

  // Entry id → its markers. An entry has one pin per location (main pin +
  // "Weitere Orte"); every one of them selects the same entry.
  const markers = new Map()
  const layer = L.markerClusterGroup({
    iconCreateFunction: buildClusterIcon,
    // The design has no coverage outline; overlapping pins fan out instead.
    showCoverageOnHover: false,
    spiderfyOnMaxZoom: true,
    // Just under the pin width, so pins only merge once they actually touch.
    maxClusterRadius: 60
  }).addTo(map)

  // The pin last clicked, so selecting an entry zooms to that location rather
  // than to its first one.
  let clickedMarker = null

  entries.forEach((entry) => {
    const list = (entry.points || [])
      .filter((point) => point.lat && point.lng)
      .map((point) => {
        const title = point.name ? `${entry.title} – ${point.name}` : entry.title
        const marker = L.marker([point.lat, point.lng], {
          icon: buildIcon(entry, pinSvg),
          title,
          alt: title,
          riseOnHover: true
        })
        marker.on('click', () => {
          clickedMarker = marker
          dispatch('eventmap:select', { id: entry.id })
        })
        return marker
      })
    if (list.length) markers.set(entry.id, list)
  })

  const allMarkers = () => [...markers.values()].flat()

  // Bulk add: the cluster group reclusters on every single addLayer call.
  layer.addLayers(allMarkers())

  // Clicking the map background clears the selection.
  map.on('click', () => dispatch('eventmap:select', { id: null }))

  frame(allMarkers())

  let visibleKey = [...markers.keys()].join(',')
  let selectedId = null

  // A clustered pin has no element, and gets a fresh one when its cluster
  // opens, so the active class is re-applied after every cluster re-render.
  layer.on('animationend', applyActive)

  el.addEventListener('eventmap:filter', (event) => {
    const ids = event.detail.ids || []
    const key = ids.join(',')
    if (key === visibleKey) return
    visibleKey = key

    const add = []
    const remove = []
    markers.forEach((list, id) => {
      const isVisible = ids.includes(id)
      list.forEach((marker) => {
        if (isVisible && !layer.hasLayer(marker)) add.push(marker)
        if (!isVisible && layer.hasLayer(marker)) remove.push(marker)
      })
    })
    if (remove.length) layer.removeLayers(remove)
    if (add.length) layer.addLayers(add)

    frame(ids.flatMap((id) => markers.get(id) || []))
  })

  el.addEventListener('eventmap:selected', (event) => {
    selectedId = event.detail.id
    applyActive()

    const list = markers.get(selectedId) || []
    const selected = list.includes(clickedMarker) ? clickedMarker : list[0]
    clickedMarker = null
    if (!selected || !layer.hasLayer(selected)) return
    // Opens the surrounding cluster first if the pin is hidden inside one.
    layer.zoomToShowLayer(selected, () => {
      map.panTo(selected.getLatLng(), { animate: true })
      applyActive()
    })
  })

  el.addEventListener('eventmap:zoom', (event) => {
    // Buttons step from the nearest whole level, as zoom can be fractional.
    map.setZoom(Math.round(map.getZoom()) + (event.detail.delta || 0))
  })

  enableWheelZoom(map)

  dispatch('eventmap:ready', {})

  function dispatch (name, detail) {
    refs.map.dispatchEvent(new CustomEvent(name, { detail, bubbles: true }))
  }

  function applyActive () {
    markers.forEach((list, id) => {
      list.forEach((marker) => {
        const element = marker.getElement()
        if (element) element.classList.toggle('is-active', id === selectedId)
      })
    })
  }

  // Frame the given markers, keeping the overlay chrome clear of the pins.
  function frame (list) {
    if (list.length > 1) {
      map.fitBounds(L.featureGroup(list).getBounds().pad(0.2))
    } else if (list.length === 1) {
      map.setView(list[0].getLatLng(), Math.max(map.getZoom(), 14))
    }
  }
}

// Wheel distance (in pixels) per zoom level. Pinch deltas are much smaller
// than scroll deltas, so pinching gets its own rate. Raise to zoom slower.
const SCROLL_PX_PER_LEVEL = 250
const PINCH_PX_PER_LEVEL = 60
// Pixels per line, for wheels reporting in lines (Firefox mouse wheel).
const LINE_PX = 40

/**
 * Continuous scroll and pinch zoom around the pointer, following the gesture
 * frame by frame (needs zoomSnap: 0). Chrome/Firefox/Edge report a pinch as a
 * wheel event with ctrlKey set, Safari as gesture events.
 */
function enableWheelZoom (map) {
  const container = map.getContainer()
  let pending = 0
  let point = null
  let frame = 0

  // Wheel events fire faster than the screen refreshes; zoom once per frame.
  const zoomBy = (delta, clientX, clientY) => {
    pending += delta
    point = map.mouseEventToContainerPoint({ clientX, clientY })
    if (frame) return
    frame = requestAnimationFrame(() => {
      frame = 0
      map.setZoomAround(point, map.getZoom() + pending, { animate: false })
      pending = 0
    })
  }

  container.addEventListener('wheel', (event) => {
    // The map takes the scroll instead of the page (and pinch instead of
    // the browser's page zoom).
    event.preventDefault()

    const pixels = event.deltaMode === 0 ? event.deltaY : event.deltaY * LINE_PX
    const rate = event.ctrlKey ? PINCH_PX_PER_LEVEL : SCROLL_PX_PER_LEVEL
    zoomBy(-pixels / rate, event.clientX, event.clientY)
  }, { passive: false })

  // Safari (non-standard GestureEvent): scale is relative to the gesture start.
  let lastScale = 1
  container.addEventListener('gesturestart', (event) => {
    event.preventDefault()
    lastScale = 1
  })
  container.addEventListener('gesturechange', (event) => {
    event.preventDefault()
    zoomBy(Math.log2(event.scale / lastScale), event.clientX, event.clientY)
    lastScale = event.scale
  })
}

/**
 * Pin marker: the designed pin shape (colour switches via CSS on .is-active)
 * with the program type glyph on top.
 */
function buildIcon (entry, pinSvg) {
  const icon = entry.icon
    ? `<img class="event-map__pin-icon" src="${encodeURI(entry.icon)}" alt="">`
    : ''

  return L.divIcon({
    className: 'event-map__pin',
    html: `${pinSvg}${icon}`,
    iconSize: [55.5, 71],
    iconAnchor: [28, 71]
  })
}

/**
 * Cluster bubble: the number of pins grouped at this point, in one of three
 * sizes. Clicking it zooms to the group's bounds (Leaflet's own behaviour).
 */
function buildClusterIcon (cluster) {
  const count = cluster.getChildCount()
  const step = CLUSTER_STEPS.find((candidate) => count <= candidate.upTo)

  return L.divIcon({
    className: `event-map__cluster event-map__cluster--${step.name}`,
    html: `<span>${count}</span>`,
    iconSize: [step.size, step.size],
    iconAnchor: [step.size / 2, step.size / 2]
  })
}
