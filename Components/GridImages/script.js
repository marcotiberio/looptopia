import * as basicLightboxModule from 'basiclightbox'
import 'basiclightbox/dist/basicLightbox.min.css'

// Get the create function from basicLightbox
const createLightbox = basicLightboxModule.create || basicLightboxModule.default?.create

export default function (el) {
  if (!createLightbox) {
    console.error('basicLightbox.create is not available')
    return
  }

  // Get all image items
  const imageItems = el.querySelectorAll('[data-ref="imageItem"]')
  if (imageItems.length === 0) return

  // Build images array from DOM
  const images = Array.from(imageItems)
    .map(item => {
      const img = item.querySelector('img')
      const src = img?.getAttribute('data-lightbox-src')
      return src ? {
        src,
        srcset: img.getAttribute('data-lightbox-srcset') || '',
        alt: img.getAttribute('alt') || ''
      } : null
    })
    .filter(Boolean)

  if (images.length === 0) return

  let currentIndex = 0
  let lightboxInstance = null
  let originalBodyOverflow = ''

  // Create lightbox HTML
  const createLightboxHTML = (index) => {
    const image = images[index]
    const hasMultiple = images.length > 1
    
    return `
      <button class="lightbox-close" aria-label="Close lightbox">&times;</button>
      ${hasMultiple ? '<button class="lightbox-nav lightbox-prev" aria-label="Previous image">‹</button>' : ''}
      ${hasMultiple ? '<button class="lightbox-nav lightbox-next" aria-label="Next image">›</button>' : ''}
      <div class="lightbox-content">
        <img 
          src="${image.src}" 
          ${image.srcset ? `srcset="${image.srcset}"` : ''}
          alt="${image.alt}"
          class="lightbox-image"
        />
      </div>
    `
  }

  // Update lightbox image
  const updateLightboxImage = (index) => {
    if (!lightboxInstance || index < 0 || index >= images.length) return
    
    const img = lightboxInstance.element().querySelector('.lightbox-image')
    if (img) {
      const image = images[index]
      img.src = image.src
      img.srcset = image.srcset || ''
      img.alt = image.alt
    }
  }

  // Attach event listeners
  const attachListeners = (instance) => {
    const element = instance.element()
    
    element.querySelector('.lightbox-close')?.addEventListener('click', () => instance.close())
    element.querySelector('.lightbox-prev')?.addEventListener('click', (e) => {
      e.stopPropagation()
      navigateImage(-1)
    })
    element.querySelector('.lightbox-next')?.addEventListener('click', (e) => {
      e.stopPropagation()
      navigateImage(1)
    })
  }

  // Navigate between images
  const navigateImage = (direction) => {
    if (images.length === 0) return
    currentIndex = (currentIndex + direction + images.length) % images.length
    updateLightboxImage(currentIndex)
  }

  // Handle keyboard events
  const handleKeyDown = (e) => {
    if (!lightboxInstance?.visible()) return

    switch (e.key) {
      case 'Escape':
        lightboxInstance.close()
        break
      case 'ArrowLeft':
        navigateImage(-1)
        break
      case 'ArrowRight':
        navigateImage(1)
        break
    }
  }

  // Open lightbox
  const openLightbox = (index) => {
    if (index < 0 || index >= images.length) return
    
    currentIndex = index

    if (!lightboxInstance) {
      // Create new lightbox
      lightboxInstance = createLightbox(createLightboxHTML(index), {
        className: 'grid-images-lightbox',
        onShow: (instance) => {
          attachListeners(instance)
          document.addEventListener('keydown', handleKeyDown)
          // Lock body scroll
          originalBodyOverflow = document.body.style.overflow
          document.body.style.overflow = 'hidden'
        },
        onClose: () => {
          document.removeEventListener('keydown', handleKeyDown)
          // Restore body scroll
          document.body.style.overflow = originalBodyOverflow
        }
      })
      lightboxInstance.show()
    } else {
      // Update existing lightbox
      const element = lightboxInstance.element()
      element.innerHTML = createLightboxHTML(index)
      attachListeners(lightboxInstance)
      if (!lightboxInstance.visible()) {
        // Lock body scroll when showing updated lightbox
        originalBodyOverflow = document.body.style.overflow
        document.body.style.overflow = 'hidden'
        lightboxInstance.show()
      }
    }
  }

  // Handle image clicks
  el.addEventListener('click', (e) => {
    const imageItem = e.target.closest('[data-ref="imageItem"]')
    if (!imageItem) return

    const img = imageItem.querySelector('img')
    const src = img?.getAttribute('data-lightbox-src')
    if (!src) return

    const index = images.findIndex(imgData => imgData.src === src)
    if (index !== -1) {
      e.preventDefault()
      e.stopPropagation()
      openLightbox(index)
    }
  })

  // Cleanup
  return () => {
    lightboxInstance?.close()
    document.removeEventListener('keydown', handleKeyDown)
    // Restore body scroll
    document.body.style.overflow = originalBodyOverflow
  }
}
