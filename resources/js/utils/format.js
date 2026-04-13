// utils/format.js
export default {
  install(app) {
    app.config.globalProperties.$capitalize = (word) => {
      if (!word || typeof word !== 'string') return '';
      return word.charAt(0).toUpperCase() + word.slice(1);
    };
  }
};