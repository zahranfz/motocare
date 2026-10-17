<?php
// MotoCare Digital - Partial: HTML Head & Scripts
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="description" content="MotoCare Digital - Motorcycle Maintenance Tracker & Smart Assistant. Pelacak servis motor berkala, rekomendasi suku cadang, pencari bengkel resmi dan umum, serta buku servis digital PDF."/>
  <title>MotoCare Digital — Smart Maintenance Tracker & Telemetry</title>

  <!-- PWA Manifest & Mobile Web App Meta -->
  <link rel="manifest" href="manifest.json"/>
  <meta name="theme-color" content="#AF101A"/>
  <meta name="mobile-web-app-capable" content="yes"/>
  <meta name="apple-mobile-web-app-capable" content="yes"/>
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
  <meta name="apple-mobile-web-app-title" content="MotoCare"/>
  <link rel="icon" type="image/svg+xml" href="assets/icons/icon.svg"/>
  <link rel="apple-touch-icon" href="assets/icons/icon.svg"/>

  <!-- Typography: Space Grotesk & Hanken Grotesk -->
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>

  <!-- Leaflet CSS for Map (FR-042) -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

  <!-- Tailwind CSS CDN with Stitch Red & Beige Design Tokens -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "surface": "#fef8f3",
            "surface-dim": "#ded9d4",
            "surface-bright": "#fef8f3",
            "surface-container-lowest": "#ffffff",
            "surface-container-low": "#f8f3ee",
            "surface-container": "#f2ede8",
            "surface-container-high": "#ece7e2",
            "surface-container-highest": "#e6e2dd",
            "on-surface": "#1d1b19",
            "on-surface-variant": "#5b403d",
            "inverse-surface": "#32302d",
            "inverse-on-surface": "#f5f0eb",
            "outline": "#8f6f6c",
            "outline-variant": "#e4beba",
            "surface-tint": "#ba1a20",
            "primary": "#af101a",
            "on-primary": "#ffffff",
            "primary-container": "#d32f2f",
            "on-primary-container": "#fff2f0",
            "secondary": "#016d38",
            "on-secondary": "#ffffff",
            "secondary-container": "#9bf6b2",
            "on-secondary-container": "#10733d",
            "tertiary": "#864700",
            "on-tertiary": "#ffffff",
            "tertiary-container": "#aa5b00",
            "on-tertiary-container": "#fff2ea",
            "error": "#ba1a1a",
            "on-error": "#ffffff",
            "error-container": "#ffdad6",
            "on-error-container": "#93000a",
            "primary-fixed": "#ffdad6",
            "on-primary-fixed": "#410003",
            "secondary-fixed": "#9bf6b2",
            "tertiary-fixed": "#ffdcc3",
            "on-tertiary-fixed": "#2f1500"
          },
          borderRadius: {
            DEFAULT: "0.125rem",
            lg: "0.25rem",
            xl: "0.5rem",
            full: "0.75rem"
          },
          spacing: {
            "gutter-lg": "1.75rem",
            "space-lg": "1.5rem",
            "margin": "1rem",
            "space-md": "1rem",
            "gutter-sm": "0.75rem",
            "margin-md": "1.5rem",
            "margin-lg": "2.5rem",
            "space-sm": "0.5rem",
            "gutter": "1.25rem",
            "space-xl": "2.5rem",
            "space-xs": "0.25rem"
          },
          fontFamily: {
            "title-lg": ["Hanken Grotesk", "sans-serif"],
            "telemetry-num": ["Space Grotesk", "monospace"],
            "headline-xl": ["Space Grotesk", "sans-serif"],
            "body-sm": ["Hanken Grotesk", "sans-serif"],
            "headline-md": ["Space Grotesk", "sans-serif"],
            "headline-lg": ["Space Grotesk", "sans-serif"],
            "label-lg": ["Hanken Grotesk", "sans-serif"],
            "body-md": ["Hanken Grotesk", "sans-serif"],
            "label-md": ["Hanken Grotesk", "sans-serif"],
            "label-sm": ["Hanken Grotesk", "sans-serif"],
            "body-lg": ["Hanken Grotesk", "sans-serif"],
            "title-md": ["Hanken Grotesk", "sans-serif"]
          },
          fontSize: {
            "title-lg": ["18px", { "lineHeight": "24px", "fontWeight": "600" }],
            "telemetry-num": ["28px", { "lineHeight": "32px", "letterSpacing": "-0.03em", "fontWeight": "700" }],
            "headline-xl": ["40px", { "lineHeight": "48px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
            "body-sm": ["12px", { "lineHeight": "16px", "fontWeight": "400" }],
            "headline-md": ["22px", { "lineHeight": "28px", "fontWeight": "600" }],
            "headline-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.015em", "fontWeight": "600" }],
            "label-lg": ["14px", { "lineHeight": "18px", "letterSpacing": "0.02em", "fontWeight": "600" }],
            "headline-xl-mobile": ["30px", { "lineHeight": "38px", "letterSpacing": "-0.01em", "fontWeight": "700" }],
            "headline-lg-mobile": ["24px", { "lineHeight": "32px", "fontWeight": "600" }],
            "body-md": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
            "label-md": ["12px", { "lineHeight": "16px", "letterSpacing": "0.04em", "fontWeight": "600" }],
            "label-sm": ["11px", { "lineHeight": "14px", "letterSpacing": "0.06em", "fontWeight": "600" }],
            "body-lg": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
            "title-md": ["16px", { "lineHeight": "22px", "fontWeight": "600" }]
          }
        }
      }
    };
  </script>

  <!-- Custom CSS for map markers, print styles, and micro-animations -->
  <link rel="stylesheet" href="assets/css/custom.css"/>

  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

  <!-- html2pdf.js for client-side PDF Generation (FR-050) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
