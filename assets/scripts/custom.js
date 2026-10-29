import $ from 'jquery'
import gsap from 'gsap'
import ScrollTrigger from 'gsap/ScrollTrigger'

// Fade-in Animation
gsap.registerPlugin(ScrollTrigger)

// responsive
const mm = gsap.matchMedia()

// Handle hash links with native scroll behavior
window.onload = () => {
  // Scroll to element id on load using native browser scroll
  const url = window.location.href
  const hash = url.substring(url.indexOf('#') + 1)
  if (hash) {
    const element = document.getElementById(hash)
    if (element) {
      element.scrollIntoView({ behavior: 'smooth', block: 'start' })
    }
  }
}

mm.add('(min-width: 1280px)', () => {
  gsap.set('.fade-in', { opacity: 0 })
  gsap.set('.move-up', { y: 200 })
})

ScrollTrigger.batch('.fade-in', {
  onEnter: (elements) =>
    gsap.to(elements, { opacity: 1, stagger: 0.1, duration: 0.5 }),
  start: '1vh bottom',
  end: 'top top',
})

ScrollTrigger.batch('.move-up', {
  onEnter: (elements) =>
    gsap.to(elements, { y: 0, stagger: 0.1, duration: 0.3 }),
  start: '1vh bottom',
  end: 'top top',
})