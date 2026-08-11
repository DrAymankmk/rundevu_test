@php
    $fb = config('app.fallback_locale', 'en');
    $locale = app()->getLocale();
    $st = $section->translation($locale) ?? $section->translation($fb);
    $secTitle = $st?->title ?: __('Services');
    $secSubtitle = $st?->subtitle ?: __('Our Services');
    $secDescription = $st?->description ?: __('Services description');
    $items = $section->relationLoaded('items') ? $section->items->where('is_active', true)->sortBy('order')->values() : collect();
@endphp

<section class="position-relative overflow-hidden space overflow-hidden" id="service-sec">
  <div class="space overflow-hidden" id="contact-sec">
        <div class="container">
            <div class="row gy-4">
                <div class="col-xl-12">
                    <form action="mail.php" method="POST" class="contact-form ajax-contact">
                        <h3 class="h4 mb-30 mt-n3">Do you have questions? Contact Us</h3>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <input type="text" class="form-control" name="name" id="name" placeholder="Name">
                                <i class="fal fa-user"></i>
                            </div>
                            <div class="form-group col-md-6">
                                <input type="tel" class="form-control" name="number" id="number" placeholder="Phone Number">
                                <i class="fal fa-phone"></i>
                            </div>
                            <div class="form-group col-12">
                                <input type="email" class="form-control" name="email" id="email" placeholder="e-mail address*">
                                <i class="fal fa-envelope"></i>
                            </div>

                            <div class="form-group col-12">
                                <select name="subject" id="subject" class="form-select nice-select">
                                    <option value="" disabled selected hidden>Select</option>
                                    <option value="General Medicinet">General Medicine</option>
                                    <option value="Heart Specialists">Heart Specialists</option>
                                    <option value="Skin & Hair Specialists">Skin & Hair Specialists</option>
                                    <option value="Child Specialists">Child Specialists</option>
                                </select>
                            </div>
                            <div class="form-group col-12">
                                <textarea name="message" id="message" cols="30" rows="3" class="form-control" placeholder="Your Message"></textarea>
                                <i class="fal fa-comment"></i>
                            </div>
                            <div class="form-btn mt-20 col-12">
                                <button class="th-btn">Send Message</button>
                            </div>
                        </div>
                        <p class="form-messages mb-0 mt-3"></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
