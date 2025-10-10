import { defineStore } from 'pinia'
import _ from 'lodash'

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [],
    customer: null,
    taxRate: 0.17, // 17% tax rate
    discount: {
      type: 'percentage', // 'percentage' or 'fixed'
      value: 0,
      reason: ''
    },
    payment: {
      method: 'cash',
      amountPaid: 0,
      change: 0
    },
    notes: ''
  }),

  getters: {
    subtotal: (state) => {
      return _.round(state.items.reduce((sum, item) => sum + (item.price * item.quantity), 0), 2)
    },
    
    discountAmount: (state) => {
      if (state.discount.type === 'percentage') {
        return _.round(state.subtotal * (state.discount.value / 100), 2)
      }
      return _.round(Math.min(state.discount.value, state.subtotal), 2)
    },
    
    taxableAmount: (state) => {
      return _.round(Math.max(0, state.subtotal - state.discountAmount), 2)
    },
    
    taxAmount: (state) => {
      return _.round(state.taxableAmount * state.taxRate, 2)
    },
    
    total: (state) => {
      return _.round(state.taxableAmount + state.taxAmount, 2)
    },
    
    changeDue: (state) => {
      return _.round(Math.max(0, state.payment.amountPaid - state.total), 2)
    },
    
    isPaid: (state) => {
      return state.payment.amountPaid >= state.total
    },
    
    itemCount: (state) => {
      return state.items.reduce((count, item) => count + item.quantity, 0)
    }
  },

  actions: {
    // Item management
    addItem(product, quantity = 1) {
      const existing = this.items.find(p => p.id === product.id)
      
      if (existing) {
        this.updateQuantity(product.id, existing.quantity + quantity)
      } else {
        this.items.push({
          ...product,
          quantity,
          total: _.round(product.price * quantity, 2),
          discount: 0
        })
      }
    },
    
    updateQuantity(productId, newQuantity) {
      const item = this.items.find(item => item.id === productId)
      if (item) {
        item.quantity = Math.max(1, newQuantity)
        item.total = _.round(item.price * item.quantity, 2)
      }
    },
    
    removeItem(id) {
      this.items = this.items.filter(p => p.id !== id)
    },
    
    // Cart operations
    clearCart() {
      this.items = []
      this.customer = null
      this.discount = { type: 'percentage', value: 0, reason: '' }
      this.payment = { method: 'cash', amountPaid: 0, change: 0 }
      this.notes = ''
    },
    
    // Discount management
    applyDiscount(value, type = 'percentage', reason = '') {
      this.discount = { type, value, reason }
    },
    
    removeDiscount() {
      this.discount = { type: 'percentage', value: 0, reason: '' }
    },
    
    // Payment handling
    setPaymentMethod(method) {
      this.payment.method = method
    },
    
    setAmountPaid(amount) {
      this.payment.amountPaid = _.round(parseFloat(amount) || 0, 2)
      this.payment.change = this.changeDue
    },
    
    // Customer management
    setCustomer(customer) {
      this.customer = customer
    },
    
    // Checkout
    async processPayment() {
      if (!this.isPaid) {
        throw new Error('Payment amount is insufficient')
      }
      
      const orderData = {
        items: this.items,
        customer: this.customer,
        subtotal: this.subtotal,
        discount: this.discount,
        tax: this.taxAmount,
        total: this.total,
        payment: this.payment,
        notes: this.notes,
        change: this.changeDue
      }
      
      // Here you would typically send this to your backend
      // const response = await axios.post('/api/checkout', orderData)
      
      // For now, we'll just log it
      console.log('Processing order:', orderData)
      
      return orderData
    }
  },
  
  // Persist cart state
  persist: {
    storage: localStorage,
    paths: ['items', 'customer', 'discount', 'payment', 'notes']
  }
})
