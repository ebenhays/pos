using PointOfSale.Data.repository;
using PointOfSale.Models;
using PointOfSale.Models.ViewModels;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace PointOfSale.Controllers
{
    public class CategoryController : Controller
    {
        private readonly CategoryRepository _repository = new CategoryRepository();

        // GET: Category
        [HttpGet]
        public ActionResult Index()
        {
            return View("/Views/Setup/Index.cshtml");
        }

        [HttpPost]
        [ActionName("Index")]
        [ValidateAntiForgeryToken]
        public ActionResult AddCategory([Bind(Include = "Name,CreationDate,CreatedBy,LastUpdatedBy,LastUpdateDate")] Category category)
        {
            try
            { 
                if (ModelState.IsValid)
                {
                 _repository.AddCategory(category);
                  TempData["Message"] = "Category Saved Successfully";
                    return RedirectToAction("Index","Setup");
                 }
                 return View("/Views/Setup/Index.cshtml");
            }
            catch (Exception)
            {

                throw;
            }
           
        }

        [HttpPost]
        [ActionName("UpdateCategory")]
        [ValidateAntiForgeryToken]
        public ActionResult UpdateCategory(Category category, int id)
        {
            if (ModelState.IsValid)
            {
                _repository.UpdateCategory(category,id);
                TempData["Message"] = "Category Updated Successfully";
                return RedirectToAction("Index","Setup");
            }
            return View("/Views/Setup/Index.cshtml");
        }

        [HttpPost]
        [ActionName("Delete")]
        //[ValidateAntiForgeryToken]
        public ActionResult DeleteCategory(int id)
        {
            if (ModelState.IsValid)
            {
                _repository.DeleteCategory(id);
                TempData["Message"] = "Category Deleted Successfully";
                return RedirectToAction("Index","Setup");
            }
            return View("/Views/Setup/Index.cshtml");
        }

        [HttpGet]
        [ActionName("EditCategory")]
        public ActionResult GetCategoryById(int id)
        {
            var getItem = _repository.GetCategory(id);
            if (getItem == null)
            {
                return RedirectToAction("Index", "Setup");
            }
            return View("GetCategoryById",getItem);
        }


        

    }
}