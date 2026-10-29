import Swiper from 'swiper'
import { Navigation, A11y, Autoplay, Pagination } from 'swiper/modules'
import 'swiper/swiper-bundle.css'
import { buildRefs, getJSON } from '@/assets/scripts/helpers.js'

export default function (sliderImages) {
  const refs = buildRefs(sliderImages)
  const data = getJSON(sliderImages)
  const swiper = initSlider(refs, data)
  return () => swiper.destroy()
}

function initSlider (refs, data) {
  const { options } = data
  const config = {
    modules: [Navigation, A11y, Autoplay, Pagination],
    a11y: options.a11y,
    slidesPerView: 'auto',
    spaceBetween: 40,
    navigation: {
      nextEl: refs.next,
      prevEl: refs.prev
    },
    pagination: {
      el: refs.dots,
      type: 'bullets',
      clickable: true
    },
    // breakpoints: {
    //   640: {
    //     slidesPerView: 4,
    //     spaceBetween: 60
    //   },
    //   1181: {
    //     slidesPerView: 7,
    //     spaceBetween: 60
    //   }
    // }
  }
  if (options.autoplay && options.autoplaySpeed) {
    config.autoplay = {
      delay: options.autoplaySpeed
    }
  }

  return new Swiper(refs.slider, config)
}
