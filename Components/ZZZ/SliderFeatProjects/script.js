import Swiper from 'swiper'
import { Navigation, A11y, Autoplay } from 'swiper/modules'
import 'swiper/swiper-bundle.css'
import { buildRefs, getJSON } from '@/assets/scripts/helpers.js'

export default function (sliderFeatProjects) {
  const refs = buildRefs(sliderFeatProjects)
  const data = getJSON(sliderFeatProjects)
  const swiper = initSlider(refs, data)
  return () => swiper.destroy()
}

function initSlider (refs, data) {
  const { options } = data
  const config = {
    modules: [Navigation, A11y, Autoplay],
    a11y: options.a11y,
    slidesPerView: 'auto',
    spaceBetween: 40,
    navigation: {
      nextEl: refs.next,
      prevEl: refs.prev
    },
    breakpoints: {
      640: {
        slidesPerView: 2,
        spaceBetween: 40
      }
    }
  }
  if (options.autoplay && options.autoplaySpeed) {
    config.autoplay = {
      delay: options.autoplaySpeed
    }
  }

  return new Swiper(refs.slider, config)
}
