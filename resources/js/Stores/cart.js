import { defineStore } from 'pinia'

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [],
  }),
  actions: {
    addItem(product) {
      const existing = this.items.find(p => p.id === product.id)
      if (existing) {
        existing.quantity++
        existing.total = existing.quantity * existing.price
      } else {
        this.items.push({ ...product, quantity: 1, total: product.price })
      }
    },
    removeItem(id) {
      this.items = this.items.filter(p => p.id !== id)
    },
    clearCart() {
      this.items = []
    },
    get total() {
      return this.items.reduce((sum, item) => sum + item.total, 0)
    },
  },
})
