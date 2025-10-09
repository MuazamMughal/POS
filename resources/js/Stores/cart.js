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
