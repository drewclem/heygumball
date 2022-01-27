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
    <BaseButton v-bind="$props">{{text}}</BaseButton>
  `,
});

Default.argTypes = {
  default: {
    control: "text",
  },
  type: {
    control: "select",
    options: ["button", "submit"],
  },
  theme: {
    control: "select",
    options: ["primary", "secondary", "tertiary"],
  },
};

Default.args = {
  default: "Subscribe Now",
  type: "button",
  theme: "primary",
};
