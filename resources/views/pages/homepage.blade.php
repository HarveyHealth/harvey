@extends('_layouts.public')
@section('page_title','Welcome to Harvey')
@section('body_class','home')

@section('content')
<div class="sections"
    :class="{'has-scroll-behavior': triggerScrollBehavior}"
    :style="showFooterStyle"
>
    <section class="section"
        id="intro"
        :class="{'current': sections[currentSection] == 'intro'}"
    >
        <div class="section-wrapper">
            <div class="container content is-medium">
                <div class="has-text-centered">
                    <img
                        src="images/logos/main-logo-lg.png"
                        srcset="images/logos/main-logo-lg@2x.png 2x"
                        alt="Harvey Logo">
                    <h2 class="title is-4 font-secondary-family tagline">Medically trained. Naturally focused.</h2>
                </div>
                <p class="copy-has-max-width">Harvey is an online telehealth provider of naturopathic medicine.  Consult face-to-face with state-licensed naturopathic doctors without leaving the comfort of your home.</p>
                <p class="copy-has-max-width">Harvey is powering the renaissance of complementary and alternative medicine by providing online access to naturopathic doctors and facilitating in-home blood draws for fast and convenient lab testing. We emphasize prevention, wellness and self-healing to combat acute and chronic conditions using evidence-based natural therapies.</p>
            </div>
        </div>
    </section>
    <section class="section"
        id="comparison"
        :class="{'current': sections[currentSection] == 'comparison'}"
    >
        <div class="section-wrapper">
            <div class="container content">
                <h2 class="title is-2 font-secondary-family">What is naturopathic medicine?</h2>
                <div class="image-wrapper"><img src="/images/home/naturopathic-medicine-graph.png" alt="Harvey"></div>
                <p class="copy-has-max-width">Naturopathic medicine is a form of primary health care that emphasizes prevention and self-healing through the use of evidence-based natural therapies.</p>
                <div class="columns has-text-centered">
                    <div class="column">
                        <figure class="icon-wrapper icon-wrapper-has-background is-lime image">
                            <span class="icon icon_comparison_1"></span>
                        </figure>
                        <p>An emphasis on disease prevention</p>
                    </div>
                    <div class="column">
                        <figure class="icon-wrapper icon-wrapper-has-background is-pink image">
                            <span class="icon icon_comparison_2"></span>
                        </figure>
                        <p>An individual approach to medicine</p>
                    </div>
                    <div class="column">
                        <figure class="icon-wrapper icon-wrapper-has-background is-brown image">
                            <span class="icon icon_comparison_3"></span>
                        </figure>
                        <p>Nutritional and holistic treatments</p>
                    </div>
                    <div class="column">
                        <figure class="icon-wrapper icon-wrapper-has-background is-purple image">
                            <span class="icon icon_comparison_4"></span>
                        </figure>
                        <p>Identifying the root cause of medical conditions</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section"
        id="steps"
        :class="{'current': sections[currentSection] == 'steps'}"
    >
        <div class="section-wrapper">
            <div class="container content">
                <h2 class="title is-2 font-secondary-family">How does Harvey work?</h2>
                <p class="copy-has-max-width">Imagine finally being able to conquer your worse chronic symptoms and flourishing. We can help you in four specific ways.</p>
                <div class="columns is-multiline">
                    <div class="column is-half columns is-mobile step-column-wrapper">
                        <div class="column">
                            <figure class="icon-wrapper icon-wrapper-has-background is-pink image">
                                <span class="icon icon_steps_1"></span>
                            </figure>
                            <span class="step-number">1</span>
                        </div>
                        <div class="column is-three-quarters">
                            <p class="title is-5"><strong>Consultation with a naturopath</strong></p>
                            <p>Consult with a licensed naturopathic doctor without leaving your home.</p>
                        </div>
                    </div>
                    <div class="column is-half columns is-mobile step-column-wrapper">
                        <div class="column">
                            <figure class="icon-wrapper icon-wrapper-has-background is-turquoise image">
                                <span class="icon icon_steps_2"></span>
                            </figure>
                            <span class="step-number">2</span>
                        </div>
                        <div class="column is-three-quarters">
                            <p class="title is-5"><strong>In-home blood draw</strong></p>
                            <p>In most cases, your doctor will recommend an in-home blood draw by a trained phlebotomist, saving you a trip to the clinic.</p>
                        </div>
                    </div>
                    <div class="column is-half columns is-mobile step-column-wrapper">
                        <div class="column">
                            <figure class="icon-wrapper icon-wrapper-has-background is-slategrey image">
                                <span class="icon icon_steps_3"></span>
                            </figure>
                            <span class="step-number">3</span>
                        </div>
                        <div class="column is-three-quarters">
                            <p class="title is-5"><strong>Health dashboards and analytics</strong></p>
                            <p>Within 7-14 days, we will send you a report  with highly accurate diagnostics of all the nutritional imbalances in your body.</p>
                        </div>
                    </div>
                    <div class="column is-half columns is-mobile step-column-wrapper">
                        <div class="column">
                            <figure class="icon-wrapper icon-wrapper-has-background is-green image">
                                <span class="icon icon_steps_4"></span>
                            </figure>
                            <span class="step-number">4</span>
                        </div>
                        <div class="column is-three-quarters">
                            <p class="title is-5"><strong>Customized vitamin and nutrition plans</strong></p>
                            <p>Your doctor will recommend a regimen to perfectly optimize  your biomarkers and attack the root cause of your symptoms, naturally.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section"
        id="number"
        :class="{'current': sections[currentSection] == 'number'}"
    >
        <div class="section-wrapper">
            <div class="container content">
                <h2 class="title is-1 font-secondary-family">1,200,100</h2>
                <p class="copy-has-max-width">The # of adults in United States and Canada who chose to receive treatment from a naturopathic doctor instead of a primary care physician in 2016. Maybe it’s time you talked to one about your health.</p>
                <div class="columns is-multiline">
                    <div class="column is-half is-one-quarter-desktop">
                        <figure class="icon-wrapper image is-64x64">
                            <span class="icon icon_number_1"></span>
                        </figure>
                        <p>Naturopathic doctors take a highly personalized approach to medicine and treat all forms of health concerns — ranging from pediatric to geriatric, irritating symptoms to chronic illness, and physical to psychological.</p>
                    </div>
                    <div class="column is-half is-one-quarter-desktop">
                        <figure class="icon-wrapper image is-64x64">
                            <span class="icon icon_number_2"></span>
                        </figure>
                        <p>Naturopathic doctors are experts in diet, nutrition, vitamins, supplements, homeopathic and botanical medicine, and many other forms of complementary and alternative therapies.</p>
                    </div>
                    <div class="column is-half is-one-quarter-desktop">
                        <figure class="icon-wrapper image is-64x64">
                            <span class="icon icon_number_3"></span>
                        </figure>
                        <p>Naturopathic doctors graduate from an accredited four-year residential naturopathic medical school and passed an extensive board examination.</p>
                    </div>
                    <div class="column is-half is-one-quarter-desktop">
                        <figure class="icon-wrapper image is-64x64">
                            <span class="icon icon_number_4"></span>
                        </figure>
                        <p>Naturopathic doctors must receive a license from their practicing state and complete 60 hours of continuing education every 2 years to maintain their license.</p>
                    </div>
                </div>
                <p class="copy-has-max-width"><strong>Harvey is making it possible to reduce the number of pharmaceutical drugs we put into our bodies and instead stimulate your body’s own natural healing abilities to combat the underlying root causes of preventable diseases, symptoms and conditions.</strong></p>
            </div>
        </div>
    </section>
    <section class="section"
        id="symptoms"
        :class="{'current': sections[currentSection] == 'symptoms'}"
    >
        <div class="section-wrapper">
            <div class="container content">
                <h2 class="title is-2 font-secondary-family">Tell us your symptoms</h2>
                <p class="copy-has-max-width">The majority of symptoms can be linked to imbalances in your biomarkers. Tell us the severity of each of your sumptoms using the sliders below, then click “Save &amp; Get Started”.</p>
                <symptoms :stats="symptomsStats" @changed="onChanged"></symptoms>
                <p class="is-clearfix">
                    <small class="disclaimer">Your selection above will only be saved if you create an account on the next page.</small>
                    <button class="button is-primary is-medium is-pulled-right" @click="getStarted" :disabled="symptomsSaving">
                        <span class="icon"
                            v-if="symptomsSaving"
                        >
                            <i class="fa fa-spinner fa-spin"></i>
                        </span>
                        <span>Save &amp; Get Started</span>
                    </button>
                </p>
            </div>
        </div>
    </section>
    <nav class="nav-sections">
        <ul>
            <li v-for="(section, index) in sections"
                :class="{'current': currentSection == index}"
            >
                <a @click="slide(index)"></a>
            </li>
        </ul>
    </nav>
    @include('_layouts.includes.footer')
</div>
@endsection
