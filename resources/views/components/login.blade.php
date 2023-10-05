<div class="xp-login">
    <h1>{{ __('Login', 'label', 'sage') }}</h1>
    @php(wp_login_form())
    <hr />
    <p class="xp-login-footer">
        <a href="{!! wp_registration_url() !!}">Registrati</a> | <a href="{!! wp_lostpassword_url() !!}">Password dimenticata</a>
    </p>
</div>
<div class="xp-login-error">
    <p id="login-error-text">Test</p>
</div>

@push('post-app-script')
<script type="module">
(function() {
        const error_container = $('.xp-login-error');
        const error_text = $('#login-error-text');
        const form = $('#loginform');
        const username = $('#user_login');
        const password = $('#user_pass');
        const remember_me = $('rememberme');

        form.on('submit', (e) => {
            e.preventDefault();
            xp.api.login(username.val(), password.val(), remember_me.is(':checked'))
                .then(() => {
                    window.location.reload();
                })
                .catch(error => {
                    error_container.addClass('show');
                    error_text.html(error.message);
                });
        });
}());
</script>
@endpush