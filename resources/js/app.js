import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Simple wishlist store (guest — localStorage)
Alpine.store('wishlist', {
    items: JSON.parse(localStorage.getItem('sd_wishlist') || '[]'),
    has(id) { return this.items.includes(id); },
    toggle(id) {
        const i = this.items.indexOf(id);
        if (i === -1) this.items.push(id); else this.items.splice(i, 1);
        localStorage.setItem('sd_wishlist', JSON.stringify(this.items));
    },
    count() { return this.items.length; },
});

// Simple cart store (guest — localStorage)
Alpine.store('cart', {
    items: JSON.parse(localStorage.getItem('sd_cart') || '[]'),
    add(item) {
        const key = `${item.product_id}-${item.variant_id ?? ''}`;
        const existing = this.items.find(i => i.key === key);
        if (existing) {
            existing.qty += item.qty ?? 1;
        } else {
            this.items.push({ key, ...item, qty: item.qty ?? 1 });
        }
        this.persist();
    },
    remove(key) {
        this.items = this.items.filter(i => i.key !== key);
        this.persist();
    },
    update(key, qty) {
        const item = this.items.find(i => i.key === key);
        if (item) { item.qty = Math.max(1, qty); this.persist(); }
    },
    clear() { this.items = []; this.persist(); },
    count() { return this.items.reduce((n, i) => n + i.qty, 0); },
    subtotal() { return this.items.reduce((s, i) => s + i.price * i.qty, 0); },
    persist() { localStorage.setItem('sd_cart', JSON.stringify(this.items)); },
});

Alpine.start();
