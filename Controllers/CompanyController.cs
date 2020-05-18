using PointOfSale.Data.repository;
using PointOfSale.Models;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace PointOfSale.Controllers
{
    public class CompanyController : Controller
    {
        private readonly CompanyRepository _repository = new CompanyRepository();
        // GET: Company
        public ActionResult Index()
        {
            return View("/Views/Setup");
        }
        [HttpPost]
        [ActionName("Index")]
        [ValidateAntiForgeryToken]
        public ActionResult AddCompany([Bind(Include = "Name,Address,ContactNo,EmailAddress,CreationDate,CreatedBy,LastUpdatedBy,LastUpdateDate")] Company company)
        {
            try
            { 
                if (ModelState.IsValid)
                {
                _repository.AddCompany(company);
                TempData["Message"] = "Company Created Successfully";
                return RedirectToAction("Index","Setup");
                }
                return View("~/Views/Setup/Index.cshtml");

            }
            catch (Exception)
            {

                throw;
            }
           
        }
    }
}