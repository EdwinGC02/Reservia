<template>
    <footer class="footer mt-auto py-3 text-center">
        <div class="container">
            <span class="text-muted"> Copyright © <span id="year">{{ currentYear }}</span> <a href="javascript:void(0);"
                    class="text-dark fw-medium">Reservia</a>.
                Desarrollado con <span class="bi bi-heart-fill text-danger"></span> Por <a href="javascript:void(0);">
                    <span class="fw-medium text-primary">E195 - UTS</span>
                </a> Todos los derechos reservados.
            </span>
        </div>
    </footer>
</template>

<script setup>
let currentYear = new Date().getFullYear()
</script>

<style lang="">

</style>
