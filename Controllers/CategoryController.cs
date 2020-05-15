using PointOfSale.Data.repository;
using PointOfSale.Models;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace PointOfSale.Controllers
{
    public class CategoryController : Controller
    {
        private readonly CategoryRepository _repository;

        public CategoryController(CategoryRepository repository) //constructor injection
        {
            _repository = repository;
        }
        // GET: Category
        public ActionResult Index()
        {
            return View();
        }

        [HttpPost]
        [ActionName("Add")]
        [ValidateAntiForgeryToken]
        public ActionResult AddCategory(Category category)
        {
            if (ModelState.IsValid)
            {
                _repository.AddCategory(category);
                return RedirectToAction("Success");
            }
            return View();
        }

        [HttpPost]
        [ActionName("Update")]
        [ValidateAntiForgeryToken]
        public ActionResult UpdateCategory(Category category, int id)
        {
            if (ModelState.IsValid)
            {
                _repository.UpdateCategory(category,id);
                return RedirectToAction("Success");
            }
            return View();
        }

        [HttpPost]
        [ActionName("Delete")]
        [ValidateAntiForgeryToken]
        public ActionResult DeleteCategory(int id)
        {
            if (ModelState.IsValid)
            {
                _repository.DeleteCategory(id);
                return RedirectToAction("Success");
            }
            return View();
        }

        [HttpGet]
        [ActionName("Update")]
        public ActionResult GetUpdateCategory()
        {
            return View();
        }

        [HttpGet]
        [ActionName("Categories")]
        public ActionResult GetCategories()
        {
            var categories = _repository.GetCategories();
            return View(categories);
        }

        [HttpGet]
        [ActionName("Categories")]
        public ActionResult GetCategories(int id)
        {
            var category = _repository.GetCategory(id);
            return View(category);
        }

        [HttpGet]
        public ActionResult Success()
        {
            return View();
        }
    }
}