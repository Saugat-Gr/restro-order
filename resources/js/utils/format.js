// utils/format.js
export default {
    install(app) {
        app.config.globalProperties.$capitalize = (word) => {
            if (!word || typeof word !== "string") return "";
            return word.charAt(0).toUpperCase() + word.slice(1);
        };
    },
};

// resources/js/utils/format.js
export const formatCurrency = (amount) => {
    return new Intl.NumberFormat("en-NP", {
        style: "currency",
        currency: "NPR",
    }).format(amount);
};
