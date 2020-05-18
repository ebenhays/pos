using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Linq;
using System.Security.Permissions;
using System.Web;
using System.Web.Mvc;

namespace PointOfSale.Models
{
	public class Category
	{
		[Key]
		public int Id { get; set; }
		[Required(ErrorMessage ="Please Enter CategoryName")]
		[StringLength(100)]
		public string Name { get; set; }
		public DateTime? CreationDate { get; set; }
		public string CreatedBy { get; set; }
		public string LastUpdatedBy { get; set; }
		public DateTime? LastUpdateDate { get; set; }
	}
	public class Company
	{
		public int Id { get; set; }
		[Required(ErrorMessage = "Please Enter Company Name")]
		[StringLength(100)]
		public string Name { get; set; }
		[StringLength(100)]
		public string Address { get; set; }
		[Required(ErrorMessage = "Please Enter Contact No")]
		public string ContactNo { get; set; }
		[StringLength(50)]
		public string EmailAddress { get; set; }
		public DateTime? CreationDate { get; set; }
		public string CreatedBy { get; set; }
		public string LastUpdatedBy { get; set; }
		public DateTime? LastUpdateDate { get; set; }
		
	}
	public class ProductStatus
	{
		public int Id { get; set; }
		[Required(ErrorMessage ="Please Enter Product Status")]
		[StringLength(50)]
		public string Status { get; set; }
		public DateTime? CreationDate { get; set; }
		[StringLength(50)]
		public string CreatedBy { get; set; }
		public string LastUpdatedBy { get; set; }
		public DateTime? LastUpdateDate { get; set; }
	}
	public class UserRole
	{
		public int Id { get; set; }
		[Required(ErrorMessage ="Please Enter the Role Name")]
		[StringLength(50)]
		public string Name { get; set; }
		public DateTime? CreationDate { get; set; }
		[StringLength(50)]
		public string CreatedBy { get; set; }
		public string LastUpdatedBy { get; set; }
		public DateTime? LastUpdateDate { get; set; }
	}
	public class UserStatus
	{
		public int Id { get; set; }

		[Required(ErrorMessage ="Please Enter the Status Name")]
		[StringLength(50)]
		public string Status { get; set; }
		public DateTime? CreationDate { get; set; }
		[StringLength(50)]
		public string CreatedBy { get; set; }
		public string LastUpdatedBy { get; set; }
		public DateTime? LastUpdateDate { get; set; }
	}
	public class Suppliers
	{
		public int Id { get; set; }
		[Required(ErrorMessage ="Please Enter Supplier Name")]
		[StringLength(100)]
		public string Name { get; set; }
		public string Address { get; set; }
		[Required(ErrorMessage ="Please Enter Supplier Contact No")]
		public string ContactNo { get; set; }
		public DateTime? CreationDate { get; set; }
		[StringLength(50)]
		public string CreatedBy { get; set; }
		public string LastUpdatedBy { get; set; }
		public DateTime? LastUpdateDate { get; set; }
	}
	public class Users
	{
		public int Id { get; set; }
		[Required(ErrorMessage ="Please Enter Username")]
		[StringLength(50)]
		public string UserName { get; set; }
		[Required(ErrorMessage ="Please Enter the User Role")]
		public virtual UserRole UserRole { get; set; }

		[Required(ErrorMessage ="Please Enter the User Status")]
		public virtual UserStatus UserStatus { get; set; }

		[Required(ErrorMessage ="User Fullname is Required")]
		[StringLength(100)]
		public string FullName { get; set; }
		public DateTime? CreationDate { get; set; }
		[StringLength(50)]
		public string CreatedBy { get; set; }
		public string LastUpdatedBy { get; set; }
		public DateTime? LastUpdateDate { get; set; }
	}
	public class Inventory
	{
		public int Id { get; set; }
		[Required]
		[Index(IsUnique = true)]
		[StringLength(100)]
		public string StockNo { get; set; }
		[Required(ErrorMessage ="Please Enter Supplier Information")]
		[StringLength(100)]
		public virtual Suppliers Supplier { get; set; }
		[StringLength(50)]
		public string BarcodeNo { get; set; }
		[Required(ErrorMessage ="Product Category is Required")]
		public virtual Category Category { get; set; }
		public string UnitType { get; set; }
		[Required(ErrorMessage ="Please Enter Cost Price")]
		public float CostPrice { get; set; }
		[Required(ErrorMessage ="Please Enter Item Per Price")]
		public float PricePerItem { get; set; }
		[Required(ErrorMessage ="Please Enter Discount Amount")]
		public float Discount { get; set; }
		[DatabaseGenerated(DatabaseGeneratedOption.Computed)]
		public float TotalCost { get; private set; }
		[Required(ErrorMessage ="Please Enter Vat Amount")]
		public float Vat { get; set; }
		[Required(ErrorMessage ="Quantity of Item is Required")]
		public int TotalQty { get; set; }
		[Required(ErrorMessage ="Manufacturing Date is required")]
		public DateTime ManufDate { get; set; }
		public DateTime? ExpiryDate { get; set; }
		[Required(ErrorMessage ="Please Enter the No of Days the system should prompt for restock")]
		public int PromptDays { get; set; }
		[Required(ErrorMessage ="Please Enter the Mininim Level an Item should reach before restocking")]
		public int MinLevelStock { get; set; }
		public string FDBNo { get; set; }
		public DateTime? CreatedDate { get; set; }
		public string CreatedBy { get; set; }
		public string LastUpdatedBy { get; set; }
		public DateTime? LastUpdateDate { get; set; }
	}
	public class Retail : Inventory
	{
		//unless there is a different an added implentation
	}
	public class WholeSale : Inventory
	{
		//unless there is a different an added implentation
	}

	
}