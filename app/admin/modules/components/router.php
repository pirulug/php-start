<?php

Router::route('components/captcha')
  ->action('components@captcha')
  ->view('components@captcha')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission("components.captcha")
  ->register();

Router::route('components/sweetalert')
  ->action('components@sweetalert')
  ->view('components@sweetalert')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission("components.sweetalert")
  ->register();

Router::route('components/gravatar')
  ->action('components@gravatar')
  ->view('components@gravatar')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission("components.gravatar")
  ->register();

Router::route('components/barcode')
  ->view('components@barcode')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission("components.barcode")
  ->register();

Router::route('components/qrcode')
  ->view('components@qrcode')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission('components.qrcode')
  ->register();

Router::route('components/fpdf')
  ->view('components@fpdf')
  ->layout('main')
  ->middleware('auth_admin')
  ->permission('components.fpdf')
  ->register();

Router::route('components/fpdf/render/{type}.pdf')
  ->action('components@fpdf')
  ->middleware('auth_admin')
  ->permission('components.fpdf')
  ->register();
