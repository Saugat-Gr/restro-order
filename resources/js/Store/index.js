import { createStore } from "vuex";
import theme from "./modules/theme.js";
import sidebar from "./modules/sidebar.js";

export default createStore({
  modules: {
    theme,
    sidebar,
  },
}); 