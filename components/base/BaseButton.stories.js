export default {
  title: "Base/Button",
};

export const Default = (args) => ({
  props: Object.keys(args),
  computed: {
    text() {
      return this.default;
    },
  },
  template: `
    <BaseButton v-bind="$prop">{{text}}</BaseButton>
  `,
});

Default.argTypes = {
  default: {
    control: "text",
  },
};

Default.args = {
  default: "Subscribe Now",
};
