import { buildRefs } from '@/assets/scripts/helpers.js'

export default (sliderLogos) => {
  const refs = buildRefs(sliderLogos)
  
  // Initialize marquee if it exists
  let marqueeInstance = null
  if (refs.marquee) {
    marqueeInstance = initMarquee(refs)
  }
  
  return () => {
    if (marqueeInstance) {
      marqueeInstance.destroy()
    }
  }
}

function initMarquee(refs) {
  const marqueeContainer = refs.marquee
  const marqueeContent = marqueeContainer.querySelector('[data-ref="marqueeContent"]')
  
  if (!marqueeContent) return null
  
  // Configuration
  const speed = 5 // pixels per second
  let animationId = null
  let position = 0
  let isPaused = false
  let contentWidth = 0
  const clones = []
  
  // Wait for DOM to be ready and calculate content width
  const initialize = () => {
    contentWidth = marqueeContent.offsetWidth
    const containerWidth = marqueeContainer.offsetWidth
    
    if (contentWidth === 0) {
      // Retry if dimensions aren't ready yet
      requestAnimationFrame(initialize)
      return
    }
    
    // Create clones - need at least 2 (original + 1 clone) for seamless loop
    // If content is shorter than container, create enough clones to fill viewport
    const minClones = 2
    const neededClones = contentWidth < containerWidth 
      ? Math.ceil((containerWidth * 2) / contentWidth) + 1 
      : minClones
    
    for (let i = 0; i < neededClones; i++) {
      const clone = marqueeContent.cloneNode(true)
      clone.setAttribute('data-ref', `marqueeContentClone${i}`)
      marqueeContent.parentNode.appendChild(clone)
      clones.push(clone)
    }
    
    // Start animation once dimensions are calculated and clones are created
    animate()
  }
  
  // Initialize after a short delay to ensure DOM is ready
  requestAnimationFrame(initialize)
  
  // Animation function
  function animate() {
    if (!isPaused && contentWidth > 0) {
      position -= speed / 60 // 60fps
      
      // Reset position for seamless loop when first item is fully off screen
      if (Math.abs(position) >= contentWidth) {
        position += contentWidth
      }
      
      // Apply transform to original content
      marqueeContent.style.transform = `translateX(${position}px)`
      
      // Apply transform to all clones
      clones.forEach((clone, index) => {
        clone.style.transform = `translateX(${position + contentWidth * (index + 1)}px)`
      })
    }
    
    animationId = requestAnimationFrame(animate)
  }
  
  // Optional: Pause on hover
  marqueeContainer.addEventListener('mouseenter', () => {
    isPaused = true
  })
  
  marqueeContainer.addEventListener('mouseleave', () => {
    isPaused = false
  })
  
  // Return cleanup function
  return {
    destroy: () => {
      if (animationId) {
        cancelAnimationFrame(animationId)
      }
      marqueeContainer.removeEventListener('mouseenter', () => {})
      marqueeContainer.removeEventListener('mouseleave', () => {})
      clones.forEach(clone => {
        if (clone && clone.parentNode) {
          clone.parentNode.removeChild(clone)
        }
      })
    }
  }
}