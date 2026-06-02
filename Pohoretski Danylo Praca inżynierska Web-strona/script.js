/* ===========================
   MOBILE NAV TOGGLE
=========================== */

document.addEventListener("DOMContentLoaded", function () {
  const mobileToggle = document.getElementById("mobileToggle");
  const navMenu = document.getElementById("navMenu");

  if (mobileToggle && navMenu) {
    mobileToggle.addEventListener("click", function () {
      navMenu.classList.toggle("active");
    });

    document.addEventListener("click", function (event) {
      const clickedOutside =
        !navMenu.contains(event.target) &&
        !mobileToggle.contains(event.target);
      if (clickedOutside && navMenu.classList.contains("active")) {
        navMenu.classList.remove("active");
      }
    });

    navMenu.querySelectorAll(".nav-link").forEach(function (link) {
      link.addEventListener("click", function () {
        navMenu.classList.remove("active");
      });
    });
  }

  /* ===========================
     ACTIVE NAV LINK
  =========================== */

  const currentPage =
    window.location.pathname.split("/").pop() || "index.html";

  document.querySelectorAll(".nav-link").forEach(function (link) {
    const href = link.getAttribute("href");
    if (href.startsWith("#")) return;
    const linkPage = href.split("/").pop().split("#")[0];
    link.classList.toggle("active", linkPage === currentPage);
  });
});

/* ===========================
   FAQ ACCORDION
=========================== */

document.querySelectorAll(".faq-item").forEach(function (item) {
  item.querySelector(".faq-question").addEventListener("click", function () {
    document
      .querySelectorAll(".faq-item")
      .forEach((other) => other !== item && other.classList.remove("active"));
    item.classList.toggle("active");
  });
});

/* ===========================
   SMOOTH SCROLL
=========================== */

document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
  anchor.addEventListener("click", function (e) {
    const href = this.getAttribute("href");
    if (href === "#") return;
    e.preventDefault();
    const target = document.querySelector(href);
    if (target) target.scrollIntoView({ behavior: "smooth", block: "start" });
  });
});

/* ===========================
   FADE-IN ON SCROLL
=========================== */

const fadeObserver = new IntersectionObserver(
  function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add("fade-in");
        fadeObserver.unobserve(entry.target);
      }
    });
  },
  { threshold: 0.1, rootMargin: "0px 0px -50px 0px" }
);

document
  .querySelectorAll(".benefit-card, .showcase-item, .plant-card, .tip-card")
  .forEach((el) => fadeObserver.observe(el));

/* ===========================
   EMAIL VALIDATION
=========================== */

function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

document.querySelectorAll('input[type="email"]').forEach(function (input) {
  input.addEventListener("blur", function () {
    this.style.borderColor =
      this.value && !isValidEmail(this.value)
        ? "var(--stem-color-primary)"
        : "";
  });
});

/* ===========================
   LANGUAGE SWITCHER
=========================== */

let currentLanguage = localStorage.getItem("language") || "en";

document.addEventListener("DOMContentLoaded", function () {
  setLanguage(currentLanguage);
  updateLanguageButtons();
});

document.querySelectorAll(".lang-btn").forEach(function (btn) {
  btn.addEventListener("click", function () {
    currentLanguage = this.getAttribute("data-lang");
    localStorage.setItem("language", currentLanguage);
    setLanguage(currentLanguage);
    updateLanguageButtons();
  });
});

function setLanguage(lang) {
  document.querySelectorAll("[data-en][data-pl]").forEach(function (el) {
    const text = el.getAttribute("data-" + lang);
    if (!text) return;
    if (el.tagName === "INPUT" || el.tagName === "TEXTAREA") {
      el.placeholder = text;
    } else {
      el.textContent = text;
    }
  });
}

function updateLanguageButtons() {
  document.querySelectorAll(".lang-btn").forEach(function (btn) {
    btn.classList.toggle(
      "active",
      btn.getAttribute("data-lang") === currentLanguage
    );
  });
}

/* ===========================
   HERO IMAGE CAROUSEL
=========================== */

document.addEventListener("DOMContentLoaded", function () {
  /* "Learn More" scroll button */
  document.querySelectorAll(".scroll-to-read-about").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const section = document.querySelector(".read-about-section");
      if (section) section.scrollIntoView({ behavior: "smooth", block: "start" });
    });
  });

  const heroMainImage = document.getElementById("heroMainImage");
  const heroThumbnails = document.querySelectorAll(".hero-thumbnail");
  if (!heroMainImage || heroThumbnails.length === 0) return;

  const images = [
    "assets/img/Product scroll photos/main1.webp",
    "assets/img/Product scroll photos/main 2.webp",
    "assets/img/Product scroll photos/main 3.webp",
    "assets/img/Product scroll photos/main 4.webp",
  ];

  let currentIndex = 0;
  let autoSlideTimer = null;

  function showImage(index) {
    heroMainImage.classList.add("fade-out");
    setTimeout(function () {
      heroMainImage.src = images[index];
      heroMainImage.classList.remove("fade-out");
      heroMainImage.classList.add("fade-in");
      setTimeout(() => heroMainImage.classList.remove("fade-in"), 500);
    }, 250);
    heroThumbnails.forEach((thumb, i) =>
      thumb.classList.toggle("active", i === index)
    );
    currentIndex = index;
  }

  function nextImage() {
    showImage((currentIndex + 1) % images.length);
  }

  function prevImage() {
    showImage((currentIndex - 1 + images.length) % images.length);
  }

  function startAutoSlide() {
    autoSlideTimer = setInterval(nextImage, 5000);
  }

  function resetAutoSlide() {
    clearInterval(autoSlideTimer);
    setTimeout(startAutoSlide, 5000);
  }

  function scrollThumbnails(direction) {
    const wrapper = document.querySelector(".hero-image-thumbnails");
    if (wrapper) wrapper.scrollBy({ left: direction * 100, behavior: "smooth" });
  }

  document
    .querySelector(".hero-thumbnail-arrow-left")
    ?.addEventListener("click", function () {
      prevImage();
      resetAutoSlide();
      scrollThumbnails(-1);
    });

  document
    .querySelector(".hero-thumbnail-arrow-right")
    ?.addEventListener("click", function () {
      nextImage();
      resetAutoSlide();
      scrollThumbnails(1);
    });

  heroThumbnails.forEach(function (thumb, index) {
    thumb.addEventListener("click", function () {
      showImage(index);
      resetAutoSlide();
    });
  });

  const heroImageContainer = document.querySelector(".hero-main-image");
  if (heroImageContainer) {
    heroImageContainer.addEventListener("mouseenter", () =>
      clearInterval(autoSlideTimer)
    );
    heroImageContainer.addEventListener("mouseleave", resetAutoSlide);
  }

  startAutoSlide();

  /* ===========================
     REVIEWS CAROUSEL (mobile)
  =========================== */

  function initReviewsCarousel() {
    const grid = document.getElementById("reviewsGrid");
    const track = document.getElementById("reviewsCarouselTrack");
    const dots = document.getElementById("reviewsCarouselDots");

    if (!grid || !track) return;

    const isMobile = window.innerWidth <= 968;
    grid.style.display = isMobile ? "none" : "grid";

    if (!isMobile) {
      track.innerHTML = "";
      if (dots) dots.innerHTML = "";
      return;
    }

    if (track.children.length > 0) return;

    const cards = Array.from(grid.querySelectorAll(".review-card"));
    if (cards.length === 0) return;

    let activeIndex = 0;

    cards.forEach(function (card) {
      const clone = card.cloneNode(true);
      ["itemprop", "itemscope", "itemtype"].forEach((attr) =>
        clone.removeAttribute(attr)
      );
      track.appendChild(clone);
    });

    function updateCarousel() {
      track.style.transform = `translateX(${-activeIndex * 100}%)`;
      if (dots) {
        dots
          .querySelectorAll(".reviews-carousel-dot")
          .forEach((dot, i) => dot.classList.toggle("active", i === activeIndex));
      }
    }

    cards.forEach(function (_, i) {
      const dot = document.createElement("button");
      dot.className = "reviews-carousel-dot" + (i === 0 ? " active" : "");
      dot.setAttribute("aria-label", "Go to review " + (i + 1));
      dot.addEventListener("click", function () {
        activeIndex = i;
        updateCarousel();
      });
      if (dots) dots.appendChild(dot);
    });

    document
      .querySelector(".reviews-carousel-arrow-left")
      ?.addEventListener("click", function () {
        activeIndex = (activeIndex - 1 + cards.length) % cards.length;
        updateCarousel();
      });

    document
      .querySelector(".reviews-carousel-arrow-right")
      ?.addEventListener("click", function () {
        activeIndex = (activeIndex + 1) % cards.length;
        updateCarousel();
      });

    let touchStartX = 0;
    track.addEventListener("touchstart", (e) => {
      touchStartX = e.changedTouches[0].screenX;
    });
    track.addEventListener("touchend", function (e) {
      const diff = touchStartX - e.changedTouches[0].screenX;
      if (Math.abs(diff) < 50) return;
      activeIndex =
        diff > 0
          ? (activeIndex + 1) % cards.length
          : (activeIndex - 1 + cards.length) % cards.length;
      updateCarousel();
    });

    window.addEventListener("resize", function () {
      const nowMobile = window.innerWidth <= 968;
      grid.style.display = nowMobile ? "none" : "grid";
      if (!nowMobile) track.style.transform = "translateX(0%)";
      else updateCarousel();
    });

    updateCarousel();
  }

  initReviewsCarousel();
  window.addEventListener("resize", initReviewsCarousel);

  /* ===========================
     PRICING MODAL
  =========================== */

  const CONTACT_EMAIL = "etiap22@gmail.com";

  const pricingModal = document.createElement("div");
  pricingModal.id = "pricingModal";
  pricingModal.className = "modal";
  pricingModal.innerHTML = `
    <div class="modal-overlay"></div>
    <div class="modal-content">
      <button class="modal-close" aria-label="Close">&times;</button>
      <h2 class="modal-title"
          data-en="Discuss Price"
          data-pl="Omów Cenę">Discuss Price</h2>
      <p class="modal-subtitle"
         data-en="Interested in our pricing? Contact us directly and we'll find the best plan for you."
         data-pl="Interesuje Cię nasz cennik? Skontaktuj się z nami, a znajdziemy dla Ciebie najlepszy plan.">
        Interested in our pricing? Contact us directly and we'll find the best plan for you.
      </p>
      <div class="pricing-modal-cta">
        <a
          href="https://mail.google.com/mail/?view=cm&fs=1&to=${CONTACT_EMAIL}&su=Pricing+Inquiry"
          target="_blank"
          rel="noopener noreferrer"
          class="btn btn-primary btn-large"
          data-en="Contact Us"
          data-pl="Skontaktuj Się"
        >Contact Us</a>
      </div>
    </div>
  `;
  document.body.appendChild(pricingModal);

  function openPricingModal() {
    setLanguage(currentLanguage);
    pricingModal.classList.add("active");
    document.body.style.overflow = "hidden";
  }

  function closePricingModal() {
    pricingModal.classList.remove("active");
    document.body.style.overflow = "";
  }

  document.querySelectorAll(".footer-pricing-btn").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      openPricingModal();
    });
  });

  pricingModal
    .querySelector(".modal-overlay")
    .addEventListener("click", closePricingModal);

  pricingModal
    .querySelector(".modal-close")
    .addEventListener("click", closePricingModal);

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && pricingModal.classList.contains("active")) {
      closePricingModal();
    }
  });
});
