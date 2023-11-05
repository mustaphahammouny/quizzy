<script setup>
import { useTemplateStore } from "@/stores/template.store";

// Template store
const template = useTemplateStore();
</script>

<template>
    <!-- Footer -->
    <footer id="page-footer">
        <slot>
            <div class="bg-body-light">
                <div class="content py-3">
                    <div class="row row-cols-sm-2 fs-sm">
                        <div class="col-12 col-sm-7 order-sm-2 py-2 py-sm-1 text-center text-sm-end">
                            <slot name="content-right">
                                Crafted with
                                <i class="fa fa-heart text-danger"></i>
                                by
                                <a class="fw-semibold" :href="template.owner.website" target="_blank">
                                    {{ template.owner.name }}
                                </a>
                            </slot>
                        </div>
                        <div class="col-12 col-sm-5 order-sm-1 py-2 py-sm-1 text-center text-sm-start">
                            <slot name="content-left">
                                <RouterLink :to="{ name: 'home' }" class="fw-semibold">
                                    {{ `${template.app.name} ${template.app.version}` }}
                                </RouterLink>
                                &copy; {{ template.app.copyright }}
                            </slot>
                        </div>
                    </div>
                </div>
            </div>
        </slot>
    </footer>
    <!-- END Footer -->
</template>
