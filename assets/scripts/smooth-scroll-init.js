import gsap from 'gsap'
import ScrollTrigger from 'gsap/ScrollTrigger'

// Register GSAP ScrollTrigger plugin (still needed for other animations)
gsap.registerPlugin(ScrollTrigger)

// Native hash link handler - using native browser scroll behavior
function handleHashLinksNative() {
  // Handle initial hash on page load
  if (window.location.hash) {
    setTimeout(() => {
      const hash = window.location.hash
      const element = document.querySelector(hash)
      if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' })
      }
    }, 100)
  }

  // Handle programmatic scrolls (e.g., from anchor links)
  document.addEventListener('click', (e) => {
    const anchor = e.target.closest('a[href^="#"]')
    if (anchor) {
      const hash = anchor.getAttribute('href')
      if (hash && hash !== '#') {
        e.preventDefault()
        const element = document.querySelector(hash)
        if (element) {
          element.scrollIntoView({ behavior: 'smooth', block: 'start' })
          // Update URL
          window.history.pushState(null, null, hash)
        }
      }
    }
  })

  // Handle browser back/forward buttons
  window.addEventListener('popstate', () => {
    const hash = window.location.hash
    if (hash) {
      const element = document.querySelector(hash)
      if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' })
      }
    }
  })
}

// Initialize native scroll behavior
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', handleHashLinksNative)
} else {
  handleHashLinksNative()
}

// Update ScrollTrigger on native scroll events
window.addEventListener('scroll', () => {
  ScrollTrigger.update()
}, { passive: true })

export default null
