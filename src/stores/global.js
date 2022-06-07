import { defineStore } from "pinia";

export const useGlobalLayout = defineStore("global", {
  state: () => {
    return {
      hasOpenModal: false,
      isMobileMenuOpen: false,
    };
  },

  actions: {
    toggleModal(modal) {
      this.hasOpenModal = modal;
    },
    toggleMobileMenu(menu) {
      this.isMobileMenuOpen = menu;
    },
  },
});
