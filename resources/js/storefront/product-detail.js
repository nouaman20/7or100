document.addEventListener('DOMContentLoaded', () => {
    const mainImage = document.querySelector('[data-gallery-active-image]');

    document.querySelectorAll('[data-gallery-thumbnail]').forEach((button) => button.addEventListener('click', () => {
        if (mainImage) {
            mainImage.src = button.dataset.imageUrl;
            mainImage.alt = button.dataset.imageAlt || '';
        }

        document.querySelectorAll('[data-gallery-thumbnail]').forEach((item) => {
            item.classList.toggle('active', item === button);
            item.setAttribute('aria-pressed', String(item === button));
        });
    }));

    const variantSelect = document.querySelector('[data-variant-select]');
    const quantityInput = document.querySelector('[data-quantity-input]');
    const price = document.querySelector('[data-variant-price]');

    const updateVariant = () => {
        const option = variantSelect?.selectedOptions[0];
        if (!option) return;

        if (price) {
            price.textContent = Number(option.dataset.price).toLocaleString(document.documentElement.lang, {
                minimumFractionDigits: 2,
            });
        }

        if (quantityInput) {
            quantityInput.max = Math.max(1, Math.min(20, Number(option.dataset.stock || 20)));
            quantityInput.value = Math.min(Number(quantityInput.value || 1), Number(quantityInput.max));
        }
    };

    variantSelect?.addEventListener('change', updateVariant);
    document.querySelectorAll('[data-quantity-step]').forEach((button) => button.addEventListener('click', () => {
        if (!quantityInput) return;

        const min = Number(quantityInput.min || 1);
        const max = Number(quantityInput.max || 20);
        quantityInput.value = Math.max(min, Math.min(max, Number(quantityInput.value || 1) + Number(button.dataset.quantityStep)));
    }));
});
