<template>
    <div class="user-object --ticket">
        <div class="user-object__image">
            <div class="image" v-bind:style="{'background-image': 'url(' + ticketImg + ')'}"></div>
        </div>
        <div class="user-object__description">
            <div class="user-object__title --ticket">
                <span>{{ticketTitle}}</span>
            </div>
            <p class="user-object__text">
                {{typeText}}
            </p>
            <div class="user-object__params">
                <div class="user-object__param --ticket">
                    <formatted-date :date="ticketDate" format="dd.MM.yyyy"/>
                </div>
                <a :href="ticketLink" target="_blank" class="user-object__download">{{ $t('profile.tickets.downloadComplaintButton') }}</a>
            </div>
        </div>
    </div>
</template>

<script>
    import FormattedDate from "~/components/FormattedDate";

    export default {
        components: {FormattedDate},
        props: [
            'ticketId',
            "ticketImg",
            "ticketTitle",
            "ticketDate",
            "ticketType",
        ],
        computed: {
            ticketLink() {
                return `/api/complaints/${this.ticketId}/doc`
            },
            typeText() {
                return this.$t(`complaintTypes.${this.ticketType}`)
            }
        }
    };
</script>