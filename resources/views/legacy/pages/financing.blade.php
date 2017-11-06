@extends('legacy._layouts.public')
@section('page_title','0% Financing')
@section('main_content')

    <section class="hero hero-background is-paddingless-bottom">
        <div id="hero-video-container">
            <div id="video-cover"></div>
        </div>
        <div class="hero-body">
            <div class="container">
                <div class="columns">
                    <div class="column has-text-centered">
                        <h1 class="title is-1">0% Loan Financing</h1>
                        <p class="subtitle copy-has-max-width">Harvey is making it even easier for patients to afford better medicine by offering 0% health loans* with instant approvals and no credit checks.</p>
                        <div class="hero-disclaimer copy-has-max-width">
                            <p>*Maximum $250 loan balance; 0% interest is achieved with a rebate; borrowers owe full finance fees to lender, which will be reimbursed in full by Harvey in the form of a credit. <a href="#get-started-disclaimer" class="legal">Learn More</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container has-text-centered">
            <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/financing/lendup-harvey.png" class="is-margin-bottom">
            <p class="copy-has-max-width subtitle is-4-desktop is-5-mobile">Harvey has teamed up with <a href="https://www.lendup.com/short-term-loans" target="_blank">LendUp</a> to provide 0% financing options to help you pay for doctor consultations, lab tests, supplements, and other treatment expenses. Our aim is simple, we want to serve the millions of Americans who have immediate health concerns but limited financial resources.</p>
        </div>
    </section>

    <section class="section">
        <div id="financing" class="container">
            <h2 class="title is-4 section-header"><span>Lending Process</span></h2>
            <div class="columns is-narrow">
                <div class="column has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-4"><strong>1. Book a consultation at Harvey</strong></h2>
                        <p>Book a consultation with a Harvey doctor. We require having a credit or debit card on file, but we will not charge it until after your consultation. <strong>You pay nothing today.</strong></p>
                    </div>
                </div>
                <div class="column has-text-right">
                    <figure>
                        <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/financing/book-doctor.png" alt="">
                    </figure>
                </div>
            </div>
            <hr>
            <div class="columns is-narrow">
                <div class="column">
                    <figure class="is-padding-left">
                        <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/financing/get-loan.png" alt="">
                    </figure>
                </div>
                <div class="column has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-4"><strong>2. Request a health loan at LendUp</strong></h2>
                        <p>Go to <a href="https://www.lendup.com/short-term-loans">LendUp.com</a> and apply for a small health loan <strong>up to $250</strong>. LendUp does not require a credit check and they give instant decisions. If approved, cash is deposited into your bank account within 24 hours.</p>
                    </div>
                </div>
            </div>
            <hr>
            <div class="columns is-narrow">
                <div class="column has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-4"><strong>3. Apply the discount to Harvey</strong></h2>
                        <p>Email your confirmation receipt to <a href="mailto:support@goharvey.com">support@goharvey.com</a> and we will credit your Harvey account equal to the total amount of their fee, which is usually about <strong>18% (or up to $50)</strong>. You can apply the discount to any lab test or consultation.</p>
                    </div>
                </div>
                <div class="column has-text-right">
                    <figure>
                        <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/financing/apply-discount.png" alt="">
                    </figure>
                </div>
            </div>
            <hr>
            <div class="columns is-narrow">
                <div class="column">
                    <figure class="is-padding-left">
                        <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/financing/repayment.png" alt="">
                    </figure>
                </div>
                <div class="column has-content-vertical-aligned">
                    <div class="content">
                        <h2 class="title is-4"><strong>4. Pay your loan over time</strong></h2>
                        <p>In order to avoid finance fees, you must repay your loan within <strong>30 days</strong>. If you need a little longer, LendUp is very flexible with their repayment options and may let you change your repayment date without penalty.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="get-started">
        <div class="container">
            <div class="has-text-centered">
                <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/financing/checkout-receipt.png">
                <h2 class="title is-3 is-padding">Apply for your 0% health loan today.</h2>
                <div class="button-wrapper">
                    <a href="https://www.lendup.com/short-term-loans" class="button is-primary is-medium has-arrow" target="_blank">Visit LendUp</a>
                </div>
            </div>
            <div id="get-started-disclaimer" class="is-margin is-padding">
                <p><strong>Loan Financing Terms & Conditions</strong></p>
                <p>"LendUp" (dba under Flurish, Inc) and Harvey Services are both corporate entities located in California and are accredited businesses by the Better Business Bureau®. Harvey has a marketing-only relationship with LendUp, and not an official partnership. LendUp does not provide, endorse, or guarantee accuracy of any information on this website. Finance and service fees quoted on this page are approximate. Please see <a href="https://www.lendup.com/short-term-loans" target="_blank">LendUp.com</a> for official lending terms. While it is unusual that LendUp rejects loan applicants, if you are not approved for a loan and would like to cancel your Harvey consultation, please cancel at least 24 hours before your scheduled appointment to avoid a cancelation fee. The offer and is availabe once per customer. If you have any questions about our financing program, please email us at <a href="mailto:support@goharvey.com" target="_blank">support@goharvey.com</a>.</p>
                <p>You can read <a href="https://www.lendup.com/terms-of-use" target="_blank">LendUp Terms of Use</a> for more information about borrowing.</p>
            </div>
        </div>
    </section>

@endsection