<template>
    <div class="complaint">
        <ViTop/>
        <MainHeader/>
        <div class="container">
            <div class="complaint__top">
                <h2 class="title">{{ $t('complaint.pageTitle') }}</h2>
                <p class="complaint__pre-text">{{ $t('complaint.infoText') }}</p>
                <p class="complaint__pre-text --required"><span>*</span>{{ $t('complaint.requiredFields') }}</p>
            </div>
        </div>
        <div class="complaint__wrapper">
            <client-only>
                <complaint-content :initial-data="initialData" :authorities="authorities" :cities="cities"/>
            </client-only>
        </div>
    </div>
</template>

<script>
    import MainHeader from "~/components/MainHeader";
    import ComplaintContent from "~/components/complaint/ComplaintContent";
    import ViTop from "~/components/ViTop";

    export default {
        head() {
            return {
                title: this.$t('complaint.pageTitle')
            }
        },
        components: {ComplaintContent, MainHeader, ViTop},
        middleware: ['authenticated'],
        async asyncData({$axios, query: {objectId}}) {
            const [{data: initialData}, {data: authorities},  {data: cities}] = await Promise.all([
                $axios.get('/api/complaints/initialData', {
                    params: {
                        objectId
                    }
                }),
                $axios.get("/api/complaints/authorities"),
                $axios.get("/api/cities")
            ]);

            return {
                initialData,
                authorities,
                cities
            }
        }
    }
</script>

<style lang="scss">
    @import "@/styles/mixins.scss";

    .complaint {
        &__wrapper {
            padding: 30px 0 60px;
            background: #F1F8FC;
            @media all and (max-width: 1023px) {
                padding: 20px 0;
                .container {
                    padding: 0;
                }
            }
        }

        &__top {
            padding: 46px 0 23px;
            @media all and (max-width: 1023px) {
                padding: 28px 0 21px;
            }
            @media all and (max-width: 768px) {
                padding: 14px 0 15px;
                .title.--md {
                    font-size: 18px;
                }
            }
        }

        &__pre-text {
            margin: 43px 0 0;
            font-size: 16px;
            line-height: 30px;
            @media all and (max-width: 1023px) {
                margin: 12px 0 0;
            }
            @media all and (max-width: 768px) {
                font-size: 14px;
                line-height: 20px;
            }

            &.--required {
                margin: 19px 0 0;
                @media all and (max-width: 1023px) {
                    margin: 10px 0 0;
                }
                @media all and (max-width: 768px) {
                    margin: 5px 0 0;
                }

                span {
                    color: #E0202E;
                }
            }
        }
    }
</style>