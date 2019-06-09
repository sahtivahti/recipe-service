const queue = require('./util/queue')
const recipes = require('./services/recipes')

queue.process('recipe.create', async (job, done) => {
  try {
    const recipe = job.data
    await recipes.create(recipe)
  } catch (e) {
    console.error(e)
  } finally {
    done()
  }
})
