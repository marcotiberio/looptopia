import Swiper from 'swiper'
import { Navigation, A11y, Autoplay } from 'swiper/modules'
import 'swiper/swiper-bundle.css'
import { buildRefs, getJSON } from '@/assets/scripts/helpers.js'

export default function (accordionSystems) {
  const refs = buildRefs(accordionSystems)
  const data = getJSON(accordionSystems)
  const swiper = initSlider(refs, data)
  return () => swiper.destroy()
}

function initSlider (refs, data) {
  const { options } = data
  const config = {
    modules: [Navigation, A11y, Autoplay],
    a11y: options.a11y,
    slidesPerView: 1,
    spaceBetween: 0,
    navigation: {
      nextEl: refs.next,
      prevEl: refs.prev
    }
  }
  if (options.autoplay && options.autoplaySpeed) {
    config.autoplay = {
      delay: options.autoplaySpeed
    }
  }

  return new Swiper(refs.slider, config)
}
