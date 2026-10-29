import Swiper from 'swiper'
import { Navigation, A11y, Keyboard } from 'swiper/modules'
import 'swiper/swiper-bundle.css'
import { buildRefs, getJSON } from '@/assets/scripts/helpers.js'

export default function (blockCarousel) {
  const refs = buildRefs(blockCarousel)
  const data = getJSON(blockCarousel)
  const swiper = initSlider(refs, data)
  return () => swiper.destroy()
}

function initSlider (refs, data) {
  const { options } = data

  return new Swiper(refs.slider, {
    modules: [Navigation, A11y, Keyboard],
    a11y: options.a11y,
    // Cards keep their design width; how many fit follows the viewport.
    slidesPerView: 'auto',
    spaceBetween: 20,
    keyboard: {
      enabled: true,
      onlyInViewport: true
    },
    // Hides the arrows and locks dragging when every card already fits.
    watchOverflow: true,
    navigation: {
      nextEl: refs.next,
      prevEl: refs.prev
    }
  })
}
