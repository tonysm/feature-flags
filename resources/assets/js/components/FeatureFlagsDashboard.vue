<template>
    <div class="container feature-flags-app">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="py-5 text-center">
                    <div class="logo m-auto" title="Deploy often, release when ready.">
                    </div>
                </div>

                <div class="justify-content-between d-flex align-items-center mb-3">
                    <h4>
                        <span class="text-muted">Flags</span>
                    </h4>

                    <a href class="btn btn-outline" @click.prevent="showNewFlagModal = true">
                        New Flag
                    </a>
                </div>


                <ul class="list-group mb-3">
                    <li class="list-group-item text-center lh-condensed py-4" v-if="flags.length === 0">
                        {{ loading ? 'Loading...' : 'No flags registed yet.' }}
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed" v-for="flag in flags" :key="flag.id">
                        <div>
                            <h6 class="my-0"><strong>{{ flag.flag }}</strong></h6>
                            <small class="text-muted" >{{ flag.description }}</small>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="mx-2 mt-2">
                                <toggle-button :value="flag.value"/>
                            </div>

                            <button
                                class="btn btn-sm m-2"
                                title="Create bypass rules."
                                @click="() => {
                                    showByPassConfirmationModal(flag);
                                }"
                            >
                                <svg width="15" height="15" class="octicon octicon-shield" viewBox="0 0 14 16" version="1.1" aria-hidden="true"><path fill-rule="evenodd" d="M7 0L0 2v6.02C0 12.69 5.31 16 7 16c1.69 0 7-3.31 7-7.98V2L7 0zM5 11l1.14-2.8a.568.568 0 0 0-.25-.59C5.33 7.25 5 6.66 5 6c0-1.09.89-2 1.98-2C8.06 4 9 4.91 9 6c0 .66-.33 1.25-.89 1.61-.19.13-.3.36-.25.59L9 11H5z"></path></svg>
                            </button>
                            <button
                                class="btn btn-sm m-2"
                                title="Archive it."
                            >
                                <svg width="15" height="15" class="octicon octicon-trashcan" viewBox="0 0 12 16" version="1.1" aria-hidden="true"><path fill-rule="evenodd" d="M11 2H9c0-.55-.45-1-1-1H5c-.55 0-1 .45-1 1H2c-.55 0-1 .45-1 1v1c0 .55.45 1 1 1v9c0 .55.45 1 1 1h7c.55 0 1-.45 1-1V5c.55 0 1-.45 1-1V3c0-.55-.45-1-1-1zm-1 12H3V5h1v8h1V5h1v8h1V5h1v8h1V5h1v9zm1-10H2V3h9v1z"></path></svg>
                            </button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <b-modal
            v-model="toggling"
            @hidden="cancelToggleOfFlag"
            @ok="confirmToggleOfFlag"
        >
            <span slot="modal-title">
                Toggle Feature Confirmation
            </span>
            <div v-if="toggling">
                <p class="alert alert-warning">
                    <strong>Warning!</strong> As this might affect users of your application, are you sure you want to do this?
                </p>
                <p>
                    You are about to <strong>{{ toggling.value ? 'disable' : 'enable' }}</strong> the feature flag: {{ toggling.flag }}.
                </p>
            </div>
        </b-modal>
        <b-modal
                v-model="bypassing"
                @hidden="cancelByPassingFlag"
                @ok="confirmByPassingFlag"
                :ok-only="true"
                ok-variant="default"
        >
            <span slot="modal-ok">Done</span>
            <span slot="modal-title">
                Register bypass rules
            </span>
            <div v-if="bypassing">
                <p class="alert alert-info">
                    <strong>Attention!</strong> Users that passes the bypass rule will have access to the feature.
                </p>

                <h4 class="mb-3">Allowed IDs</h4>
                <div class="row">
                    <div class="container">
                        <div class="form-row align-items-center">
                            <div class="col-sm-4 my-1">
                                <label class="sr-only" >Allow ID</label>
                                <input type="text" class="form-control" placeholder="Example: 123" />
                            </div>
                            <div class="col-auto my-1">
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <p class="text-muted">The IDs listed bellow all have access to this feature.</p>

                        <ul class="list-group">
                            <li class="list-group-item justify-content-between d-flex">
                                <span>ID: 123</span>

                                <button class="btn btn-sm">Remove</button>
                            </li>
                        </ul>
                    </div>
                </div>
                </div>
        </b-modal>
        <b-modal
            v-model="showNewFlagModal"
            cancel-variant="default"
            @ok="createFeatureFlag"
        >
            <span slot="modal-ok">Save</span>
            <span slot="modal-cancel">Close</span>
            <span slot="modal-title">
                New Feature Flag
            </span>
            <div>
                <form>
                    <div class="form-group">
                        <label for="flag">Flag</label>
                        <input type="text" class="form-control" id="flag" v-model="newFlag.flag" aria-describedby="flagHelp" placeholder="EXAMPLE_FLAG" />
                    </div>
                    <div class="form-group">
                        <label for="shortDescription">Short Description</label>
                        <input type="text" v-model="newFlag.description" class="form-control" id="shortDescription" placeholder="Lorem ipsum." />
                    </div>
                    <div class="form-group">
                        <div class="">
                            <toggle-button
                                v-model="newFlag.value"
                            />
                            <span class="ml-2">
                                {{ newFlag.value ? 'Start enabled' : 'Start disabled' }}
                            </span>
                        </div>
                    </div>
                </form>
            </div>
        </b-modal>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                showToggleConfirmationModal: false,
                showNewFlagModal: false,
                toggling: null,
                loading: false,
                flags: [],
                bypassing: null,
                newFlag: {
                    flag: '',
                    description: '',
                    value: false,
                    bypass: false,
                    rules: []
                },
            };
        },
        methods: {
            confirmToggleOfFlag () {
                this.toggling.value = !this.toggling.value;
            },
            cancelToggleOfFlag () {
                this.toggling = null;
            },
            showByPassConfirmationModal (flag) {
                this.bypassing = flag;
            },
            confirmByPassingFlag () {
                this.bypassing.bypass = !this.bypassing.bypass;
            },
            cancelByPassingFlag () {
                this.bypassing = null;
            },
            createFeatureFlag () {
                axios.post('/feature-flags/flags', this.newFlag)
                    .then(({data}) => {
                        this.flags.push(data);
                    });
            }
        },
        mounted() {
            this.loading = true;

            axios.get('/feature-flags/flags')
                .then(({data}) => {
                    this.flags = data;
                    this.loading = false;
                });
        }
    }
</script>

<style scoped>
    .feature-flags-app {
        padding-top: 10px;
    }
</style>