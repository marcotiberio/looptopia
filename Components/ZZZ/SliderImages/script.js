import Swiper from 'swiper'
import { Navigation, A11y, Autoplay, Pagination } from 'swiper/modules'
import 'swiper/swiper-bundle.css'
import { buildRefs, getJSON } from '@/assets/scripts/helpers.js'

export default function (sliderBox) {
  const refs = buildRefs(sliderBox)
  const data = getJSON(sliderBox)
  const swiper = initSlider(refs, data)
  return () => swiper.destroy()
}

function initSlider (refs, data) {
  const { options } = data
  const config = {
    modules: [Navigation, A11y, Autoplay, Pagination],
    a11y: options.a11y,
    slidesPerView: 1,
    spaceBetween: 0,
    navigation: {
      nextEl: refs.next,
      prevEl: refs.prev
    },
    // pagination: {
    //   el: refs.dots,
    //   type: 'bullets',
    //   clickable: true
    // },
    breakpoints: {
      640: {
        slidesPerView: 1,
        spaceBetween: 0
      },
      1181: {
        slidesPerView: 1,
        spaceBetween: 0
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
