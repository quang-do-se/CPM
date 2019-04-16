<template>
    <div class="container">

        <div class="row justify-content-center">
            <h2> Search Phecode</h2>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <search-select2></search-select2>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="card col-md-8" v-for="card in cards" style="margin-top:15px">
                <div class="card-body">
                    <p><b>Phecode:</b> {{ card.phecode }}</p>
                    <p><b>Phecode Description:</b> {{ card.phecodeDescription }}</p>
                    <p><b>ICD9 Code:</b> {{ card.icd9 }}</p>
                    <p><b>ICD9 Description:</b> {{ card.icd9Description }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
        },
        data() {
            return {
                cards: []
            }
        },
        created() {
            EventBus.$on('search-select2', this.fetchCodes);
        },
        methods: {
            fetchCodes(data) {
                console.log('Code Card Select');

                console.log(data);

                let page_url = './api/searchICD9Phecode?phecode=' + data.id;
                fetch(page_url)
                    .then(res => res.json())
                    .then(json => {
                        this.cards = json.data;
                    })
                    .catch(err => console.log(err));
            }
        }
    }
</script>
