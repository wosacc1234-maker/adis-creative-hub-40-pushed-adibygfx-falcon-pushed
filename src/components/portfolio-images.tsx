// Portfolio image imports
import portfolioLogo1 from "@/assets/portfolio-logo-1.jpg"
import portfolioThumbnails1 from "@/assets/portfolio-thumbnails-1.jpg"
import portfolioVideo1 from "@/assets/portfolio-video-1.jpg"
import portfolioBranding1 from "@/assets/portfolio-branding-1.jpg"
import portfolioLogo2 from "@/assets/portfolio-logo-2.jpg"
import portfolioThumbnails2 from "@/assets/portfolio-thumbnails-2.jpg"
import beforeAfterLogo from "@/assets/before-after-logo.jpg"
import beforeAfterThumbnail from "@/assets/before-after-thumbnail.jpg"
import blogHeader1 from "@/assets/blog-header-1.jpg"

export const portfolioImages = {
  logos: [portfolioLogo1, portfolioLogo2],
  thumbnails: [portfolioThumbnails1, portfolioThumbnails2],
  video: [portfolioVideo1],
  branding: [portfolioBranding1],
  beforeAfter: {
    logo: beforeAfterLogo,
    thumbnail: beforeAfterThumbnail
  },
  blog: [blogHeader1]
}

// Alt text for SEO
export const portfolioImageAlts = {
  portfolioLogo1: "Professional logo design for tech startup - modern corporate branding showcase by Adil GFX",
  portfolioThumbnails1: "High-converting YouTube gaming thumbnails with bold text overlays and vibrant colors",
  portfolioVideo1: "Professional video editing workspace showing creative video production process",
  portfolioBranding1: "Complete YouTube channel branding mockup with logo, thumbnails and channel art",
  portfolioLogo2: "E-commerce logo design showcase - minimalist brand identity for online business",
  portfolioThumbnails2: "Viral cooking YouTube thumbnails with appetizing food photography and engaging text",
  beforeAfterLogo: "Before and after logo design comparison showing brand transformation results",
  beforeAfterThumbnail: "Before and after YouTube thumbnail comparison demonstrating CTR improvement",
  blogHeader1: "Creative design blog header with modern typography and professional layout"
}