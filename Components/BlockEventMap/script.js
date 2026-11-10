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
 *   in  — eventmap:filter { ids }, eventmap:selected { id }, eventmap:zoom { delta }, eventmap:print { format }
 *   out — eventmap:ready, eventmap:select { id }, eventmap:printlist { groups }
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

  const tiles = L.tileLayer(refs.map.dataset.tileUrl, {
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

  let visibleIds = [...markers.keys()]
  let visibleKey = visibleIds.join(',')
  let selectedId = null

  // A clustered pin has no element, and gets a fresh one when its cluster
  // opens, so the active class is re-applied after every cluster re-render.
  layer.on('animationend', applyActive)

  el.addEventListener('eventmap:filter', (event) => {
    const ids = event.detail.ids || []
    const key = ids.join(',')
    if (key === visibleKey) return
    visibleKey = key
    visibleIds = ids

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

  el.addEventListener('eventmap:print', (event) => printMap(event.detail && event.detail.format))

  dispatch('eventmap:ready', {})

  /**
   * Print the current view. The map switches to the paper size and the
   * clusters (which can't be opened on paper) are replaced:
   *   list   — A4: numbered dots, with a legend below the map listing what
   *            each number stands for
   *   poster — A2: every pin on its own, as designed
   */
  function printMap (format) {
    const root = document.documentElement
    if (root.classList.contains(PRINT_CLASS)) return

    const isPoster = format === 'poster'
    const classes = isPoster ? [PRINT_CLASS, PRINT_POSTER_CLASS] : [PRINT_CLASS]
    const view = map.getBounds()
    const pageStyle = document.createElement('style')
    // A2 has no CSS page-size keyword, so the poster is given in millimetres.
    pageStyle.textContent = `@page { size: ${isPoster ? '594mm 420mm' : 'A4 landscape'}; margin: 10mm; }`
    document.head.appendChild(pageStyle)
    root.classList.add(...classes)
    // The canvas has its print size now; show the same area as before.
    map.invalidateSize({ animate: false })
    map.fitBounds(view, { animate: false })

    let printLayer
    // The poster is four times the area of the screen map, so it gets tiles
    // one level deeper at half size — otherwise the basemap prints blurred.
    let printTiles = tiles
    if (isPoster) {
      // Use the whole sheet for the pins that were in view.
      const inView = allMarkers()
        .filter((marker) => layer.hasLayer(marker) && view.contains(marker.getLatLng()))
      if (inView.length > 1) {
        map.fitBounds(L.featureGroup(inView).getBounds(), {
          animate: false,
          paddingTopLeft: [POSTER_PADDING, POSTER_PADDING * 2],
          paddingBottomRight: [POSTER_PADDING, POSTER_PADDING]
        })
      }
      const pins = buildPosterPins(entries, visibleIds, map, pinSvg)
      printLayer = L.layerGroup(pins)
      printTiles = L.tileLayer(refs.map.dataset.tileUrl, {
        ...tiles.options,
        tileSize: 128,
        zoomOffset: 1
      }).addTo(map)
      map.removeLayer(tiles)
      dispatch('eventmap:printlist', { groups: [], places: pins.length })
    } else {
      const groups = buildPrintGroups(entries, visibleIds, map)
      printLayer = L.layerGroup(groups.map(buildDot))
      dispatch('eventmap:printlist', {
        groups: groups.map(({ n, items }) => ({ n, items })),
        places: groups.length
      })
    }
    printLayer.addTo(map)
    map.removeLayer(layer)

    const restore = () => {
      map.removeLayer(printLayer)
      map.addLayer(layer)
      if (printTiles !== tiles) {
        map.removeLayer(printTiles)
        map.addLayer(tiles)
      }
      root.classList.remove(...classes)
      pageStyle.remove()
      map.invalidateSize({ animate: false })
      map.fitBounds(view, { animate: false })
      applyActive()
    }
    window.addEventListener('afterprint', restore, { once: true })

    // Print once the tiles of the resized map are in (or after a grace period
    // on a slow connection) and Alpine has rendered the legend.
    let printed = false
    const print = () => {
      if (printed) return
      printed = true
      requestAnimationFrame(() => window.print())
    }
    setTimeout(() => {
      if (!printTiles.isLoading()) return print()
      printTiles.once('load', print)
      setTimeout(print, PRINT_TILE_TIMEOUT)
    }, 100)
  }

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

// Set on <html> while the print layout is active — see _style.scss.
const PRINT_CLASS = 'is-printing-event-map'
const PRINT_POSTER_CLASS = 'is-printing-event-map--poster'
// Longest wait for map tiles before printing anyway.
const PRINT_TILE_TIMEOUT = 8000
// Poster: pins closer than this count as the same spot and are set side by
// side at this spacing, since a stack can't be fanned out on paper.
const POSTER_SAME_SPOT = 6
const POSTER_SPOT_SPACING = 30
// Poster pins are drawn smaller than on screen so neighbours stay apart —
// keep in sync with $poster-pin-scale in _style.scss.
const POSTER_PIN_SCALE = 0.65
// Space kept free between the outermost pins and the edge of the sheet.
const POSTER_PADDING = 60
// Dot diameter on the printed map, and the distance below which neighbouring
// pins share one dot (and one legend row) instead of overprinting each other.
const PRINT_DOT_SIZE = 18
const PRINT_MERGE_DISTANCE = 16
// Dots are numbered in reading order: in bands of this height, left to right.
const PRINT_BAND_HEIGHT = 60

/**
 * Numbered groups for the printed map: every pin of the filtered entries that
 * lies in the current view, merged with its neighbours where they would
 * overlap on paper.
 */
function buildPrintGroups (entries, visibleIds, map) {
  const bounds = map.getBounds()
  const groups = []

  entries
    .filter((entry) => visibleIds.includes(entry.id))
    .forEach((entry) => {
      (entry.points || []).forEach((point, index) => {
        if (!point.lat || !point.lng || !bounds.contains([point.lat, point.lng])) return

        const position = map.latLngToContainerPoint([point.lat, point.lng])
        // The address belongs to the main location (the first point) only, and
        // is left out where the venue name already spells it out.
        const street = (entry.address || '').split(',')[0]
        const address = index === 0 && !(street && point.name.includes(street)) ? entry.address : ''
        const item = {
          title: entry.title,
          org: entry.org,
          place: [point.name, address].filter(Boolean).join(', '),
          times: (entry.schedule || []).map((row) => `${row.date} ${row.time}`.trim()).filter(Boolean).join(' · ')
        }

        let group = groups.find((candidate) => candidate.position.distanceTo(position) < PRINT_MERGE_DISTANCE)
        if (!group) {
          group = { position, latLngs: [], items: [] }
          groups.push(group)
        }
        group.latLngs.push(L.latLng(point.lat, point.lng))
        const isListed = group.items.some((other) => other.title === item.title && other.place === item.place)
        if (!isListed) group.items.push(item)
      })
    })

  const band = (group) => Math.floor(group.position.y / PRINT_BAND_HEIGHT)
  return groups
    .sort((a, b) => band(a) - band(b) || a.position.x - b.position.x)
    .map((group, index) => ({
      n: index + 1,
      latLng: L.latLngBounds(group.latLngs).getCenter(),
      items: group.items
    }))
}

/**
 * Poster pins: one designed pin per location of the filtered entries in the
 * current view. Pins sharing a spot are spread sideways around it.
 */
function buildPosterPins (entries, visibleIds, map, pinSvg) {
  const bounds = map.getBounds()
  const spots = []

  entries
    .filter((entry) => visibleIds.includes(entry.id))
    .forEach((entry) => {
      (entry.points || []).forEach((point) => {
        if (!point.lat || !point.lng || !bounds.contains([point.lat, point.lng])) return

        const position = map.latLngToContainerPoint([point.lat, point.lng])
        let spot = spots.find((candidate) => candidate.position.distanceTo(position) < POSTER_SAME_SPOT)
        if (!spot) {
          spot = { position, entries: [] }
          spots.push(spot)
        }
        // An entry listing the same place twice still gets one pin there.
        if (!spot.entries.includes(entry)) spot.entries.push(entry)
      })
    })

  return spots.flatMap((spot) => spot.entries.map((entry, index) => {
    const shift = (index - (spot.entries.length - 1) / 2) * POSTER_SPOT_SPACING
    const latLng = map.containerPointToLatLng(spot.position.add([shift, 0]))
    return L.marker(latLng, {
      interactive: false,
      keyboard: false,
      icon: buildIcon(entry, pinSvg, POSTER_PIN_SCALE)
    })
  }))
}

function buildDot (group) {
  return L.marker(group.latLng, {
    interactive: false,
    keyboard: false,
    icon: L.divIcon({
      className: 'event-map__dot',
      html: `<span>${group.n}</span>`,
      iconSize: [PRINT_DOT_SIZE, PRINT_DOT_SIZE],
      iconAnchor: [PRINT_DOT_SIZE / 2, PRINT_DOT_SIZE / 2]
    })
  })
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
function buildIcon (entry, pinSvg, scale = 1) {
  const icon = entry.icon
    ? `<img class="event-map__pin-icon" src="${encodeURI(entry.icon)}" alt="">`
    : ''

  return L.divIcon({
    className: 'event-map__pin',
    html: `${pinSvg}${icon}`,
    iconSize: [55.5 * scale, 71 * scale],
    iconAnchor: [28 * scale, 71 * scale]
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
