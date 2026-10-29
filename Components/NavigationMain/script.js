// import gsap from 'gsap'

// export default function init() {
//   const mainMenu = document.querySelector('.mainMenu')
  
//   if (!mainMenu) return

//   // Set initial state - hidden and positioned below
//   gsap.set(mainMenu, { 
//     opacity: 0,
//     y: 50 // Start 50px below final position
//   })

//   // Wait for page animation to finish (1.8s total: 1s delay + 0.8s fade out)
//   // Then wait 1 more second before sliding up the menu
//   gsap.to(mainMenu, {
//     opacity: 1,
//     y: 0, // Slide to final position
//     duration: 0.5,
//     ease: 'power2.out',
//     delay: 2 // 1.8s (page animation) + 1s (additional delay)
//   })
// }
