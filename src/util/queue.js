const kue = require('kue')

const queue = kue.createQueue({
  redis: {
    host: process.env.REDIS_HOST || 'redis'
  }
})

module.exports = queue
