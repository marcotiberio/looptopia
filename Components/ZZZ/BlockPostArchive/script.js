const scrollElements = document.querySelectorAll('.archiveComponent.animated')

const elementInView = (el, dividend = 1) => {
  const elementLeft = el.getBoundingClientRect().left

  return (
    elementLeft <=
    (window.innerWidth || document.documentElement.clientWidth) / dividend
  )
}

const elementOutofView = (el) => {
  const elementLeft = el.getBoundingClientRect().Left

  return (
    elementLeft > (window.innerWidth || document.documentElement.clientWidth)
  )
}

const displayScrollElement = (element) => {
  element.classList.add('fade-in')
}

const hideScrollElement = (element) => {
  element.classList.remove('fade-in')
}

const handleScrollAnimation = () => {
  scrollElements.forEach((el) => {
    if (elementInView(el, 1.25)) {
      displayScrollElement(el)
    } else if (elementOutofView(el)) {
      hideScrollElement(el)
    }
  })
}

window.addEventListener('wheel', () => {
  handleScrollAnimation()
})
