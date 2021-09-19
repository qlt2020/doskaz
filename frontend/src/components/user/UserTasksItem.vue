<template>
  <div class="user-tasks__item">
    <div class="user-tasks__item-left">
      <div class="user-tasks__item-status" :class="tasksItemStatus"></div>
      <!--<div class="user-tasks__item-date">{{ tasksItemDate }}</div>-->
      <formatted-date
        element="div"
        class="user-tasks__item-date"
        format="d MMMM"
        :date="tasksItemDate"
      />
      <div class="user-tasks__item-text">{{ tasksItemText }}</div>
    </div>
    <div class="user-tasks__item-points">
      {{ $tc("profile.tasks.pointsCount", tasksItemPoints) }}
    </div>
  </div>
</template>

<script>
import FormattedDate from "~/components/FormattedDate";
export default {
  components: { FormattedDate },
  props: [
    "tasksItemStatus",
    "tasksItemDate",
    "tasksItemText",
    "tasksItemPoints"
  ]
};
</script>
