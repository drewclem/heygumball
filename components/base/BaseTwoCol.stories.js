export default {
  title: 'Base components/Two Column Layout',
};

export const Default = (args) => ({
  template: `
    <BaseTwoCol>
      <template v-slot:left>
        <BaseImage src="https://source.unsplash.com/random" alt="Alternate text" />
      </template>

      <template v-slot:right>
        <BaseText>Our residents attend daily 12-step meetings and practice the principles of the 12-steps in their daily lives through sponsorship and mentorship.</BaseText>
      </template>
    </BaseTwoCol>
  `,
});
