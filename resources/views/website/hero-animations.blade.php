
<script>
// ========== ANIMATIONS & INTERACTIONS ==========

// Lazy Loading Images
const observerOptions = {
  threshold: 0.1,
  rootMargin: '50px'
};

const imageObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const img = entry.target;
      if (img.dataset.src) {
        img.src = img.dataset.src;
      }
      img.classList.add('loaded');
      imageObserver.unobserve(img);
    }
  });
}, observerOptions);

// Observe all lazy images
document.querySelectorAll('img[data-src]').forEach(img => {
  imageObserver.observe(img);
});

// Section Visibility Animation
const sectionObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
    }
  });
}, { threshold: 0.1 });

document.querySelectorAll('section').forEach(section => {
  sectionObserver.observe(section);
});

// Product Cards Animation
const productObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('loaded');
    }
  });
}, { threshold: 0.1 });

document.querySelectorAll('.product-card').forEach((card, index) => {
  card.style.animationDelay = (index * 0.1) + 's';
  productObserver.observe(card);
});

// Parallax Scroll Effect for Hero
const heroContent = document.querySelector('.hero-content');
const heroImage = document.querySelector('.hero-image');

window.addEventListener('scroll', () => {
  const scrollY = window.scrollY;
  if (heroContent) heroContent.style.transform = `translateY(${scrollY * 0.3}px)`;
  if (heroImage) heroImage.style.transform = `translateY(${scrollY * 0.5}px)`;
});

// Scroll Indicator
const scrollIndicator = document.querySelector('.scroll-indicator');
if (scrollIndicator) {
  scrollIndicator.classList.add('show');
  
  window.addEventListener('scroll', () => {
    if (window.scrollY > 200) {
      scrollIndicator.classList.remove('show');
    } else {
      scrollIndicator.classList.add('show');
    }
  });
}

// Sticky Header Hide/Show on Scroll
let lastScrollTop = 0;
const header = document.querySelector('nav');
if (header) {
  window.addEventListener('scroll', () => {
    let scrollTop = window.scrollY;
    if (scrollTop > lastScrollTop && scrollTop > 200) {
      header.classList.add('nav-hidden');
    } else {
      header.classList.remove('nav-hidden');
    }
    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
  });
}

// Product Favorite Toggle
document.querySelectorAll('.product-fav').forEach(btn => {
  btn.addEventListener('click', function(e) {
    e.preventDefault();
    this.classList.toggle('liked');
    this.textContent = this.classList.contains('liked') ? '❤️' : '🤍';
  });
});

// Smooth Scroll for Hash Links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function(e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      target.scrollIntoView({ behavior: 'smooth' });
    }
  });
});

// Mobile Menu Toggle
const hamburger = document.querySelector('.nav-hamburger');
const navLinks = document.querySelector('.nav-links');
if (hamburger && navLinks) {
  hamburger.addEventListener('click', () => {
    navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
  });
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
  console.log('✅ Hero animations initialized');
  console.log('✅ Lazy loading active');
  console.log('✅ Scroll effects ready');
});
</script>
