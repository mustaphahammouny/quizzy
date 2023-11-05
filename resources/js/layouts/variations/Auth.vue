<script setup>
import { useTemplateStore } from "@/stores/template.store";

// Template store
const template = useTemplateStore();
</script>

<template>
    <!-- Page Content -->
    <div class="bg-primary-dark">
        <div class="row g-0 bg-primary-dark-op">
            <!-- Meta Info Section -->
            <div class="hero-static col-lg-4 d-none d-lg-flex flex-column justify-content-center">
                <div class="p-4 p-xl-5 flex-grow-1 d-flex align-items-center">
                    <div class="w-100">
                        <RouterLink :to="{ name: 'home' }" class="link-fx fw-semibold fs-2 text-white">
                            {{ template.app.name }}
                        </RouterLink>
                        <p class="text-justify text-white-75 mt-2">
                            {{ template.app.description }}
                        </p>
                    </div>
                </div>
                <div class="p-4 p-xl-5 d-xl-flex justify-content-between align-items-center fs-sm">
                    <p class="fw-medium text-white-50 mb-0">
                        <strong>{{ `${template.app.name} ${template.app.version}` }}</strong>
                        &copy; {{ template.app.copyright }}
                    </p>
                    <ul class="list list-inline mb-0 py-2">
                        <li class="list-inline-item">
                            <a class="text-white-75 fw-medium" href="javascript:void(0)">Legal</a>
                        </li>
                        <li class="list-inline-item">
                            <a class="text-white-75 fw-medium" href="javascript:void(0)">Contact</a>
                        </li>
                        <li class="list-inline-item">
                            <a class="text-white-75 fw-medium" href="javascript:void(0)">Terms</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- END Meta Info Section -->

            <!-- Main Section -->
            <div class="hero-static col-lg-8 d-flex flex-column align-items-center bg-body-extra-light">
                <div class="p-3 w-100 d-lg-none text-center">
                    <RouterLink :to="{ name: 'home' }" class="link-fx fw-semibold fs-3 text-dark">
                        {{ template.app.name }}
                    </RouterLink>
                </div>
                <div class="p-4 w-100 flex-grow-1 d-flex align-items-center">
                    <div class="w-100">
                        <slot></slot>
                    </div>
                </div>
                <div
                    class="px-4 py-3 w-100 d-lg-none d-flex flex-column flex-sm-row justify-content-between fs-sm text-center text-sm-start">
                    <p class="fw-medium text-black-50 py-2 mb-0">
                        <strong>{{ `${template.app.name} ${template.app.version}` }}</strong>
                        &copy; {{ template.app.copyright }}
                    </p>
                    <ul class="list list-inline py-2 mb-0">
                        <li class="list-inline-item">
                            <a class="text-muted fw-medium" href="javascript:void(0)">Legal</a>
                        </li>
                        <li class="list-inline-item">
                            <a class="text-muted fw-medium" href="javascript:void(0)">Contact</a>
                        </li>
                        <li class="list-inline-item">
                            <a class="text-muted fw-medium" href="javascript:void(0)">Terms</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- END Main Section -->
        </div>

        <!-- Terms Modal -->
        <div class="modal fade" id="modal-terms" tabindex="-1" role="dialog" aria-labelledby="modal-terms"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-popout" role="document">
                <div class="modal-content">
                    <BaseBlock title="Terms &amp; Conditions" transparent class="mb-0">
                        <template #options>
                            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </template>

                        <template #content>
                            <div class="block-content">
                                <p>
                                    Dolor posuere proin blandit accumsan senectus netus nullam
                                    curae, ornare laoreet adipiscing luctus mauris adipiscing
                                    pretium eget fermentum, tristique lobortis est ut metus
                                    lobortis tortor tincidunt himenaeos habitant quis dictumst
                                    proin odio sagittis purus mi, nec taciti vestibulum quis in
                                    sit varius lorem sit metus mi.
                                </p>
                                <p>
                                    Dolor posuere proin blandit accumsan senectus netus nullam
                                    curae, ornare laoreet adipiscing luctus mauris adipiscing
                                    pretium eget fermentum, tristique lobortis est ut metus
                                    lobortis tortor tincidunt himenaeos habitant quis dictumst
                                    proin odio sagittis purus mi, nec taciti vestibulum quis in
                                    sit varius lorem sit metus mi.
                                </p>
                                <p>
                                    Dolor posuere proin blandit accumsan senectus netus nullam
                                    curae, ornare laoreet adipiscing luctus mauris adipiscing
                                    pretium eget fermentum, tristique lobortis est ut metus
                                    lobortis tortor tincidunt himenaeos habitant quis dictumst
                                    proin odio sagittis purus mi, nec taciti vestibulum quis in
                                    sit varius lorem sit metus mi.
                                </p>
                                <p>
                                    Dolor posuere proin blandit accumsan senectus netus nullam
                                    curae, ornare laoreet adipiscing luctus mauris adipiscing
                                    pretium eget fermentum, tristique lobortis est ut metus
                                    lobortis tortor tincidunt himenaeos habitant quis dictumst
                                    proin odio sagittis purus mi, nec taciti vestibulum quis in
                                    sit varius lorem sit metus mi.
                                </p>
                                <p>
                                    Dolor posuere proin blandit accumsan senectus netus nullam
                                    curae, ornare laoreet adipiscing luctus mauris adipiscing
                                    pretium eget fermentum, tristique lobortis est ut metus
                                    lobortis tortor tincidunt himenaeos habitant quis dictumst
                                    proin odio sagittis purus mi, nec taciti vestibulum quis in
                                    sit varius lorem sit metus mi.
                                </p>
                                <p>
                                    Dolor posuere proin blandit accumsan senectus netus nullam
                                    curae, ornare laoreet adipiscing luctus mauris adipiscing
                                    pretium eget fermentum, tristique lobortis est ut metus
                                    lobortis tortor tincidunt himenaeos habitant quis dictumst
                                    proin odio sagittis purus mi, nec taciti vestibulum quis in
                                    sit varius lorem sit metus mi.
                                </p>
                            </div>
                            <div class="block-content block-content-full text-end bg-body">
                                <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">
                                    Close
                                </button>
                            </div>
                        </template>
                    </BaseBlock>
                </div>
            </div>
        </div>
        <!-- END Terms Modal -->
    </div>
    <!-- END Page Content -->
</template>
