<footer class="footer"
    :class="{'is-simple': !guest}">
    <div class="container">
        <div class="content has-text-centered">
            <a href="/">
                @if (Auth::guest())
                    <img src="/images/logos/white-logo.png" alt="Harvey Logo">
                @else
                    <img src="/images/logos/main-logo.png" alt="Harvey Logo">
                @endif
            </a>
            <div id="social-icons">
                <!-- Facebook -->
                <a href="https://www.facebook.com/goharveyapp" target="_blank">
                    <?php print '<?xml version="1.0" ?>'?><svg  style="width: 30px; height: 30px;" enable-background="new 0 0 32 32" version="1.1" viewBox="0 0 32 32" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Flat_copy"><g><g><path d="M16,31.625c-8.616,0-15.625-7.01-15.625-15.625C0.375,7.384,7.384,0.375,16,0.375     c8.615,0,15.625,7.009,15.625,15.625C31.625,24.615,24.615,31.625,16,31.625z" fill="#FFFFFF"/><path d="M16,0.75c8.409,0,15.25,6.841,15.25,15.25S24.409,31.25,16,31.25S0.75,24.409,0.75,16S7.591,0.75,16,0.75      M16,0C7.163,0,0,7.163,0,16c0,8.836,7.163,16,16,16s16-7.164,16-16C32,7.163,24.837,0,16,0L16,0z" fill="#E5E5E5"/></g></g><path d="M13.69,24.903h3.679v-8.904h2.454l0.325-3.068h-2.779l0.004-1.536c0-0.8,0.076-1.229,1.224-1.229h1.534   V7.097h-2.455c-2.949,0-3.986,1.489-3.986,3.992v1.842h-1.838v3.069h1.838V24.903z" fill="#ADA397"/></g></svg>
                </a>
                <!-- Medium  -->
                 <a href="https://blog.goharvey.com" target="_blank">
                    <?php print '<?xml version="1.0" ?>'?><svg  style="width: 30px; height: 30px;" enable-background="new 0 0 32 32" version="1.1" viewBox="0 0 32 32" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Style_2_copy_4"><g><g><g><path d="M16,31.625c-8.616,0-15.625-7.01-15.625-15.625C0.375,7.384,7.384,0.375,16,0.375      c8.615,0,15.625,7.009,15.625,15.625C31.625,24.615,24.615,31.625,16,31.625z" fill="#FFFFFF"/><path d="M16,0.75c8.409,0,15.25,6.841,15.25,15.25S24.409,31.25,16,31.25S0.75,24.409,0.75,16      S7.591,0.75,16,0.75 M16,0C7.163,0,0,7.163,0,16c0,8.836,7.163,16,16,16s16-7.164,16-16C32,7.163,24.837,0,16,0L16,0z" fill="#E5E5E5"/></g></g></g><g><path d="M24.959,11.076l-5.76-2.88c-0.026-0.013-0.055-0.019-0.083-0.02c-0.002,0-0.004-0.001-0.006-0.001    c-0.07,0-0.14,0.035-0.177,0.096l-3.55,5.769l3.963,6.44l5.669-9.212C25.056,11.201,25.031,11.112,24.959,11.076z" fill="#ADA397"/><polygon fill="#ADA397" points="13.505,12.013 13.505,18.23 19.031,20.992   "/><path d="M19.514,21.23l5.074,2.537c0.277,0.138,0.504-0.002,0.504-0.311v-11.29L19.514,21.23z" fill="#ADA397"/><path d="M12.895,11.105L7.58,8.448L7.244,8.28C7.196,8.256,7.15,8.244,7.108,8.244    c-0.059,0-0.109,0.023-0.145,0.066C6.929,8.352,6.908,8.412,6.908,8.487v12.13c0,0.206,0.151,0.451,0.336,0.543l5.222,2.611    c0.073,0.036,0.142,0.053,0.204,0.053c0.176,0,0.299-0.136,0.299-0.365V11.226C12.97,11.175,12.941,11.128,12.895,11.105z" fill="#ADA397"/></g></g></svg>
                </a>
                <!-- YouTube-->
                  <a href="https://www.youtube.com/channel/UCNW4aHA1yCPUdk7OM65oNDw" target="_blank">
                    <?php print '<?xml version="1.0" ?>'?><svg  style="width: 30px; height: 30px;" enable-background="new 0 0 32 32" version="1.1" viewBox="0 0 32 32" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Flat_copy"><g><g><path d="M16,31.62c-8.616,0-15.625-7.01-15.625-15.625S7.384,0.37,16,0.37c8.615,0,15.625,7.01,15.625,15.625     S24.615,31.62,16,31.62z" fill="#FFFFFF"/><path d="M16,0.745c8.409,0,15.25,6.841,15.25,15.25S24.409,31.245,16,31.245S0.75,24.404,0.75,15.995     S7.591,0.745,16,0.745 M16-0.005c-8.837,0-16,7.163-16,16c0,8.836,7.163,16,16,16s16-7.164,16-16C32,7.158,24.837-0.005,16-0.005     L16-0.005z" fill="#E5E5E5"/></g></g><g><path d="M21.15,19.585c-0.146,0-0.25,0.041-0.314,0.128c-0.063,0.082-0.094,0.222-0.094,0.417v0.47h0.809v-0.47    c0-0.195-0.033-0.335-0.097-0.417C21.392,19.626,21.289,19.585,21.15,19.585z" fill="#ADA397"/><path d="M17.996,19.568c-0.066,0-0.13,0.014-0.194,0.045c-0.063,0.03-0.126,0.078-0.189,0.142v2.899    c0.074,0.074,0.147,0.132,0.217,0.164c0.07,0.033,0.143,0.05,0.221,0.05c0.112,0,0.194-0.032,0.246-0.099    c0.052-0.065,0.08-0.17,0.08-0.32v-2.402c0-0.157-0.033-0.277-0.097-0.357C18.215,19.608,18.119,19.568,17.996,19.568z" fill="#ADA397"/><path d="M23.079,15.962c-0.752-0.75-7.082-0.76-7.082-0.76c0-0.001-6.33,0.009-7.083,0.759    c-0.753,0.752-0.755,4.462-0.755,4.483c0,0.028,0.002,3.732,0.755,4.484c0.753,0.75,7.083,0.766,7.083,0.766    s6.331-0.016,7.082-0.766c0.756-0.754,0.763-4.484,0.763-4.484C23.842,20.424,23.833,16.714,23.079,15.962z M12.987,18.149h-1.066    v5.39h-1.031v-5.39H9.823v-0.916h3.163V18.149z M16,23.538h-0.913v-0.511c-0.17,0.189-0.345,0.333-0.529,0.431    c-0.183,0.1-0.36,0.148-0.531,0.148c-0.212,0-0.369-0.068-0.478-0.208c-0.105-0.139-0.16-0.345-0.16-0.623v-3.89h0.914v3.568    c0,0.111,0.019,0.19,0.056,0.24c0.04,0.05,0.103,0.074,0.188,0.074c0.067,0,0.153-0.032,0.256-0.099    c0.103-0.065,0.197-0.148,0.284-0.249v-3.534H16V23.538z M19.319,22.577c0,0.329-0.072,0.581-0.215,0.757    c-0.142,0.176-0.351,0.263-0.622,0.263c-0.18,0-0.339-0.032-0.478-0.101c-0.14-0.068-0.272-0.171-0.391-0.315v0.357h-0.924v-6.306    h0.924v2.031c0.124-0.142,0.254-0.251,0.392-0.323c0.14-0.074,0.28-0.111,0.422-0.111c0.29,0,0.511,0.099,0.663,0.296    c0.154,0.199,0.229,0.489,0.229,0.87V22.577z M22.491,21.325h-1.749V22.2c0,0.245,0.03,0.415,0.089,0.51    c0.062,0.095,0.164,0.141,0.31,0.141c0.151,0,0.257-0.04,0.318-0.12c0.06-0.08,0.092-0.256,0.092-0.531v-0.212h0.941v0.239    c0,0.477-0.114,0.836-0.344,1.079c-0.228,0.241-0.571,0.36-1.026,0.36c-0.409,0-0.733-0.127-0.968-0.383    c-0.235-0.255-0.354-0.606-0.354-1.055v-2.089c0-0.402,0.131-0.733,0.389-0.986c0.258-0.254,0.591-0.381,1.001-0.381    c0.419,0,0.741,0.118,0.966,0.353c0.225,0.235,0.336,0.573,0.336,1.014V21.325z" fill="#ADA397"/></g><path d="M21.146,13.251h-1.03v-0.566c-0.188,0.208-0.386,0.368-0.592,0.476c-0.205,0.109-0.404,0.164-0.597,0.164   c-0.24,0-0.418-0.077-0.538-0.229c-0.12-0.153-0.181-0.381-0.181-0.687V8.126h1.028v3.929c0,0.121,0.024,0.209,0.067,0.263   c0.042,0.055,0.113,0.082,0.209,0.082c0.075,0,0.171-0.036,0.287-0.108c0.115-0.072,0.222-0.164,0.317-0.276v-3.89h1.03   L21.146,13.251L21.146,13.251z" fill="#ADA397"/><path d="M16.965,8.369c-0.273-0.248-0.623-0.373-1.049-0.373c-0.467,0-0.841,0.118-1.117,0.353   c-0.278,0.235-0.417,0.551-0.416,0.949v2.656c0,0.436,0.136,0.782,0.405,1.04c0.271,0.257,0.632,0.386,1.082,0.386   c0.469,0,0.835-0.125,1.104-0.374c0.266-0.25,0.399-0.593,0.399-1.031V9.327C17.372,8.937,17.236,8.617,16.965,8.369z    M16.323,12.052c0,0.136-0.041,0.244-0.123,0.324c-0.083,0.078-0.193,0.117-0.332,0.117c-0.137,0-0.242-0.038-0.314-0.115   c-0.073-0.078-0.111-0.186-0.111-0.326V9.26c0-0.112,0.04-0.203,0.118-0.272c0.079-0.069,0.181-0.103,0.307-0.103   c0.135,0,0.245,0.034,0.329,0.103c0.084,0.069,0.126,0.16,0.126,0.272V12.052z" fill="#ADA397"/><polygon fill="#ADA397" points="11.578,6.305 10.41,6.305 11.79,10.499 11.79,13.251 12.95,13.251 12.95,10.369 14.299,6.305    13.12,6.305 12.404,9.081 12.331,9.081  "/></g></svg>
                </a>
                <!-- Instagram -->
                <a href="https://www.instagram.com/goharveyapp/" target="_blank">
                    <?php print '<?xml version="1.0" ?>'?><svg  style="width: 30px; height: 30px;" enable-background="new 0 0 32 32" version="1.1" viewBox="0 0 32 32" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Flat_copy"><g><g><path d="M16,31.62c-8.615,0-15.625-7.01-15.625-15.625S7.385,0.37,16,0.37s15.625,7.01,15.625,15.625     S24.615,31.62,16,31.62z" fill="#FFFFFF"/><path d="M16,0.745c8.409,0,15.25,6.841,15.25,15.25S24.409,31.245,16,31.245S0.75,24.404,0.75,15.995     S7.591,0.745,16,0.745 M16-0.005c-8.837,0-16,7.163-16,16c0,8.836,7.163,16,16,16s16-7.164,16-16C32,7.158,24.837-0.005,16-0.005     L16-0.005z" fill="#E5E5E5"/></g></g><path d="M22.057,7.924H9.943c-1.14,0-2.019,0.879-2.019,2.019v12.113c0,1.14,0.879,2.019,2.019,2.019h12.113   c1.14,0,2.019-0.879,2.019-2.019V9.943C24.076,8.803,23.196,7.924,22.057,7.924z M16.012,12.827c1.791,0,3.243,1.407,3.243,3.142   c0,1.735-1.452,3.142-3.243,3.142c-1.79,0-3.242-1.408-3.242-3.142C12.77,14.234,14.222,12.827,16.012,12.827z M22.057,21.552   c0,0.354-0.151,0.505-0.505,0.505H10.448c-0.353,0-0.505-0.151-0.505-0.505v-7.066l1.258,0.274   c-0.135,0.439-0.208,0.903-0.208,1.385c0,2.684,2.248,4.863,5.018,4.863c2.772,0,5.019-2.178,5.019-4.863   c0-0.482-0.073-0.946-0.208-1.385l1.234-0.274V21.552z M22.057,12.467c0,0.279-0.226,0.505-0.505,0.505h-2.019   c-0.279,0-0.505-0.226-0.505-0.505v-2.019c0-0.279,0.226-0.505,0.505-0.505h2.019c0.279,0,0.505,0.226,0.505,0.505V12.467z" fill="#ADA397"/></g></svg>
                </a>
                <!-- Twitter   -->
                <a href="https://twitter.com/goharveyapp" target="_blank">
                    <?php print '<?xml version="1.0" ?>'?><svg  style="width: 30px; height: 30px;" enable-background="new 0 0 32 32" version="1.1" viewBox="0 0 32 32" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Flat_copy"><g><g><path d="M16,31.625c-8.616,0-15.625-7.01-15.625-15.625S7.384,0.375,16,0.375c8.615,0,15.625,7.01,15.625,15.625     S24.615,31.625,16,31.625z" fill="#FFFFFF"/><path d="M16,0.75c8.409,0,15.25,6.841,15.25,15.25S24.409,31.25,16,31.25S0.75,24.409,0.75,16S7.591,0.75,16,0.75      M16,0C7.163,0,0,7.163,0,16c0,8.836,7.163,16,16,16s16-7.164,16-16C32,7.163,24.837,0,16,0L16,0z" fill="#E5E5E5"/></g></g><path d="M18.226,8.886c-1.59,0.579-2.595,2.071-2.481,3.704l0.038,0.63l-0.636-0.077   c-2.315-0.296-4.338-1.299-6.056-2.984l-0.84-0.836L8.036,9.94c-0.458,1.376-0.165,2.83,0.789,3.808   c0.509,0.54,0.394,0.617-0.483,0.296c-0.305-0.103-0.573-0.18-0.598-0.141c-0.089,0.09,0.216,1.26,0.458,1.724   c0.331,0.644,1.005,1.273,1.743,1.647l0.624,0.296L9.83,17.581c-0.712,0-0.738,0.013-0.661,0.284   c0.254,0.836,1.259,1.724,2.379,2.11l0.789,0.27l-0.687,0.412c-1.018,0.593-2.214,0.927-3.41,0.951   c-0.573,0.013-1.044,0.064-1.044,0.103c0,0.128,1.553,0.848,2.455,1.132c2.71,0.836,5.929,0.475,8.346-0.952   c1.718-1.016,3.435-3.036,4.237-4.992c0.433-1.041,0.865-2.945,0.865-3.858c0-0.592,0.038-0.669,0.75-1.376   c0.42-0.412,0.814-0.862,0.891-0.99c0.128-0.245,0.114-0.245-0.534-0.026c-1.081,0.386-1.234,0.335-0.699-0.244   c0.394-0.412,0.865-1.158,0.865-1.376c0-0.038-0.191,0.026-0.407,0.141c-0.229,0.129-0.738,0.322-1.12,0.437l-0.687,0.219   L21.535,9.4c-0.344-0.231-0.826-0.489-1.081-0.566C19.804,8.654,18.812,8.68,18.226,8.886z" fill="#ADA397"/></g></svg>
                </a>
            </div>
            <p class="nav-center">
                <a href="/" class="nav-item">Home</a>
                <a href="/lab-tests" class="nav-item">Labs</a>
                <a href="https://blog.goharvey.com" class="nav-item">Blog</a>
                <a href="http://help.goharvey.com" class="nav-item">Help</a>
                <a href="/terms" class="nav-item">Terms</a>
                <a href="/privacy" class="nav-item">Privacy</a>
            </p>
            <div id="mc_embed_signup" class="is-hidden-mobile">
                <form action="//goharvey.us15.list-manage.com/subscribe/post?u=dc828d195bee3640b849c2838&amp;id=93440a985d" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                    <div id="mc_embed_signup_scroll">
                    <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="Your email goes here..." required>
                    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_dc828d195bee3640b849c2838_93440a985d" tabindex="-1" value=""></div>
                    <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
                    </div>
                </form>
            </div>
            <p class="has-small-lineheight">
                <small>&copy;{{date("Y")}} Harvey, Inc. All rights reserved.<br/>
                Harvey does not provide medical advice, diagnosis or treatment.</small>
            </p>
        </div>
    </div>
</footer>