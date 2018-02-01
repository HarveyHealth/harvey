@extends('legacy._layouts.public')
@section('page_title','Lab Tests')
@section('main_content')

<section class="hero hero-background">
    <div id="hero-video-container">
        <video id="hero-video" autoplay loop muted></video>
        <div id="video-cover"></div>
        <div id="overlay"></div>
    </div>
    <div class="hero-body">
        <div class="container">
            <div class="columns">
                <div class="column is-7 is-6-desktop">
                    <h1 class="title is-1">Home Lab Testing</h1>
                    <p class="subtitle is-5">Our integrative doctors rely on a wide range of specialized, in-home lab tests to help validate and enhance the credibility of their diagnosis and treatment plans.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" id="tests">
    <div class="container">
        <h2 class="title font-lg section-header"><span>Most Popular</span></h2>
        <div class="columns is-narrow">
            <div class="column has-content-vertical-aligned">
                <div class="content">
                    <h2 class="title is-4"><strong>Micronutrient Test</strong></h2>
                    <p>Micronutrient blood tests measure your body's level of absorption of 35 different nutritional components in your white blood cells — including vitamins, minerals, antioxidants, amino acids, fatty acids and other essential nutrients.</p>
                    <p>Scientific evidence shows us that analyzing the white blood cells gives us a very accurate analysis of a body's deficiencies that are often related to functional disorders.</p>
                </div>
            </div>
            <div class="column has-text-right">
                <figure>
                    <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/micronutrient.jpg" alt="">
                </figure>
            </div>
        </div>
        <hr>
        <div class="columns is-narrow">
            <div class="column">
                <figure>
                    <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/allergy.jpg" alt="">
                </figure>
            </div>
            <div class="column has-content-vertical-aligned">
                <div class="content">
                    <h2 class="title is-4"><strong>Food Allergy Test</strong></h2>
                    <p>Individuals with neurological, gastrointestinal or skin disorders often suffer from food allergies. Many people continue to eat offending foods unknowingly for years, unaware of their potential effects.</p>
                    <p>Our food allergy test helps identify even the most hypersensitive of allergies caused by over 93 different foods across multiple food groups, such as dairy, fruit, fish, meat, nuts, grains and vegetables.</p>
                </div>
            </div>
        </div>
        <hr>
        <div class="columns is-narrow">
            <div class="column has-content-vertical-aligned">
                <div class="content">
                    <h2 class="title is-4"><strong>Hormone Test</strong></h2>
                    <p>Our hormone panel is an important test for anyone concerned with changing hormone levels in their body, often due to age. Common symptoms of hormone imbalances include fatigue, insomnia, stress, mood swings, low libido, immunity or weight imbalances.</p>
                    <p>Hormones influence all aspects of health and disease, including metabolism, heart health and physical appearance.</p>
                </div>
            </div>
            <div class="column has-text-right">
                <figure>
                    <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/hormone.jpg" alt="">
                </figure>
            </div>
        </div>
    </div>
</section>

<section class="section" id="feature">
    <div class="container">
        <div class="mw9 center ph3-ns">
          <div class="cf ph2-ns">
            <div class="fl w-100 w-50-l pa2">
              <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/home/package-door.jpg" alt="">
            </div>
            <div class="fl w-100 w-50-l pa2">
              <div class="pt5">
                <h2 class="title"><strong>Lab testing made simple.</strong></h2>
                <p>All our lab tests can be taken in the comfort of your home. You will need to supply us with a urine sample, stool sample or blood draw. We will mail you a lab kit and arrange an in-home blood draw (if necessary) by one of our blood draw technicians located nationwide.</p>
                <p class="pt3">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur vel fermentum libero. In luctus lobortis auctor. In venenatis velit nec nisi euismod vestibulum. Sed auctor blandit rhoncus. Morbi non tristique magna.</p>
              </div>
            </div>
          </div>
        </div>
    </div>
</section>

<section class="section is-narrow">
    <div class="container">
        <h2 class="title font-lg section-header"><span>Our Lab Tests</span></h2>
        <div class="container bg-white">
            <vertical-tabs load-with-id="{{ $index + 1 }}">
                @foreach ($lab_tests as $lab_test)
                    <vertical-tab class="tab" label="{{ $lab_test->sku->name }}" url="{{ $lab_test->sku->slug }}">
                        <header class="level">
                            <div class="media-left is-pulled-left">
                                <img src="{{ $lab_test->image }}" alt="">
                            </div>
                            <div class="media-content">
                                <h3 class="title font-xl"><strong>{{ $lab_test->sku->name }} Test</strong></h3>
                                @if ($lab_test->example)
                                    <a class="link font-lg" href="{{ $lab_test->example }}" target="_blank">What does this test measure?</a>
                                @endif
                                <p class="font-md">Sample: {{ $lab_test->sample }}</p>
                            </div>
                            <div class="media-right">
                                <p class="title is-3">${{ number_format($lab_test->sku->price) }}</p>
                            </div>
                        </header>
                        <div class="content">
                            <blockquote>"{!! $lab_test->quote !!}"</blockquote>
                            {!! $lab_test->description !!}
                        </div>
                        <div class="mt2">
                            <a href="/lab-tests" class="button is-primary font-md has-arrow ph4">Add to Cart</a>
                        </div>
                    </vertical-tab>
                @endforeach
            </vertical-tabs>
        </div>
    </div>
</section>

@endsection
