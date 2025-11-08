@props(['status'])

<div class="alert flex right center {{ $status }}">
    <b>{{ session($status) }}</b>
</div>
<script>
setTimeout(() => {
    document.querySelector(".alert").style.display = "none";
}, 2e3);
</script>
