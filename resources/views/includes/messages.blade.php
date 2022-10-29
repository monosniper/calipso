@if (session('success'))
    <div class="message success">{{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="message error">{{ session('error') }}</div>
@endif

@if (session('info'))
    <div class="message info">{{ session('info') }}</div>
@endif
