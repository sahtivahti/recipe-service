const kue = require('kue')

const queue = kue.createQueue({
  redis: {
    host: 'redis'
  }
})

module.exports = (req, res) => {
  const recipe = {
    id: 'foo',
    name: 'bar'
  }

  queue.create('create.recipe', recipe).save()

  res.json(recipe)
}
