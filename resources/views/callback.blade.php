<script>
    window.opener.postMessage({ status: '{{ $status }}' }, window.location.origin);
    window.close();
</script>
