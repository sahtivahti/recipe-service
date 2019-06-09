const recipes = require('../../../services/recipes')

module.exports = async (req, res) => {
  const id = req.params.id
  console.log(req.params)
  const recipe = await recipes.get(id)
  res.json(recipe)
}
