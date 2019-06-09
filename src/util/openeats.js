const got = require('got')

const user = 'openeats'
const pass = 'openeats123'
const baseUri = `http://${user}:${pass}@openeats-api:8000/api/v1/`

const createRecipe = async (recipe) => {
  return await got.post(`${baseUri}recipe/recipes/`, { body: recipe, json: true })
}

const listRecipes = async () => {
  return (await got(`${baseUri}recipe/recipes/`, { json: true })).body
}

const getRecipe = async (recipeId) => {
  return (await got(`${baseUri}recipe/recipes/${recipeId}`, { json: true })).body
}

const deleteRecipe = async (recipeId) => {
  return (await got.delete(`${baseUri}recipe/recipes/${recipeId}`, { json: true })).body
}

module.exports = { createRecipe, listRecipes, getRecipe, deleteRecipe }
