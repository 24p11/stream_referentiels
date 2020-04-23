<template>
    <div class="form-group">
        <input
                :placeholder="placeholder"
                @input="search"
                autofocus
                class="form-control"
                required="required"
                type="search"
                v-model="searchTerm">
        <br>
        <Result :noResult="noResult" :perform-search="performSearch" :repositories="repositories"/>
    </div>
</template>

<script>
    import Result from './Result'
    import debounce from "lodash.debounce";

    const axios = require('axios');

    export default {
        created() {
            this.searchAction = debounce(search, 200)
        },
        data: () => {
            return {
                placeholder: `Rechercher dans ${type}`,
                searchTerm: '',
                performSearch: false,
                repositories: [],
            }
        },
        methods: {
            search() {
                if (this.searchTerm) {
                    this.performSearch = true
                    this.searchAction(this)
                }
            },
        },
        computed: {
            noResult: function () {
                return !this.performSearch && this.searchTerm && !this.repositories.length
            }
        },
        components: {
            Result
        }
    }

    function search($this) {
        axios.get(referential_api_version, {
            params: {
                type: type,
                search: $this.searchTerm
            }
        })
            .then((response) => {
                $this.repositories = response.data
            })
            .then(() => {
                $this.performSearch = false;
            });
    }
</script>
