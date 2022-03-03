export default {
  title: 'Base components/Icon',
};

export const Default = (args) => ({
  props: Object.keys(args),
  template:
    '<div class="relative w-12 h-12 ml-32 mt-24"><BaseIcon v-bind="$props" :class="color" /></div>',
});

Default.argTypes = {
  file: {
    control: {
      type: 'select',
      options: ['address-icon', 'phone-icon'],
    },
  },
  alt: {
    control: 'text',
  },
  color: {
    control: {
      type: 'select',
      options: [
        'text-primary-light',
        'text-primary-dark',
        'text-secondary-light',
        'text-secondary-dark',
      ],
    },
  },
};

Default.args = {
  file: 'address-icon',
  alt: 'Icon alt text',
  color: 'text-primary-dark',
};
